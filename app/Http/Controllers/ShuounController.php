<?php
namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use App\Models\RegistrationForm;
use App\Models\FormTemplate;
use App\Models\ReceiptNumber;
use App\Models\AdvisorAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;

class ShuounController extends Controller {

    // ==================== Dashboard ====================

    public function dashboard() {
        $pending  = RegistrationForm::where('status', 'pending_shuoun')
            ->with('student')->latest()->get();
        $approved = RegistrationForm::where('status', 'approved')
            ->with('student')->latest()->take(20)->get();
        $rejected = RegistrationForm::where('status', 'rejected')
            ->with('student')->latest()->take(10)->get();
        $stats = [
            'pending'  => RegistrationForm::where('status', 'pending_shuoun')->count(),
            'approved' => RegistrationForm::where('status', 'approved')->count(),
            'total'    => RegistrationForm::count(),
        ];
        return view('shuoun.dashboard', compact('pending', 'approved', 'rejected', 'stats'));
    }

    // ==================== Forms ====================

    public function showForm($id) {
        $form = RegistrationForm::with('student')->findOrFail($id);
        return view('shuoun.form', compact('form'));
    }

    public function approveForm(Request $request, $id) {
        $form = RegistrationForm::findOrFail($id);
        $hash = Str::random(64);

        // مجلد الـ QR
        $qrDir = storage_path('app/public/qrcodes');
        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        // توليد QR Code PNG
        $qrContent  = route('verify', $hash);
        $qrPath     = 'qrcodes/' . $hash . '.png';
        $qrFullPath = storage_path('app/public/' . $qrPath);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->data($qrContent)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size(300)
            ->margin(10)
            ->build();

        file_put_contents($qrFullPath, $result->getString());

        $form->update([
            'status'       => 'approved',
            'approved_by'  => Auth::id(),
            'approved_at'  => now(),
            'unique_hash'  => $hash,
            'qr_code_path' => $qrPath,
        ]);

        $this->generatePDF($form->fresh());

        return redirect()->route('shuoun.dashboard')
            ->with('success', 'تمت الموافقة وتوليد الورقة الرسمية بنجاح');
    }

    public function rejectForm(Request $request, $id) {
        $request->validate(['reason' => 'required|string|max:500']);
        $form = RegistrationForm::findOrFail($id);
        $form->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);
        return redirect()->route('shuoun.dashboard')
            ->with('info', 'تم رفض الطلب');
    }

    private function generatePDF(RegistrationForm $form) {
        $pdfDir = storage_path('app/public/pdfs');
        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0755, true);
        }
        $tempDir = storage_path('app/mpdf_temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $mpdf = new Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'orientation'   => 'P',
            'margin_top'    => 15,
            'margin_bottom' => 15,
            'margin_left'   => 15,
            'margin_right'  => 15,
            'tempDir'       => $tempDir,
        ]);

        $mpdf->SetDirectionality('rtl');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont   = true;
        $mpdf->autoArabic       = true;

        $html = view('pdf.registration', ['form' => $form])->render();
        $mpdf->WriteHTML($html);

        $pdfPath  = 'pdfs/' . $form->unique_hash . '.pdf';
        $fullPath = storage_path('app/public/' . $pdfPath);
        $mpdf->Output($fullPath, 'F');
        $form->update(['pdf_path' => $pdfPath]);
    }

    // ==================== Templates ====================

    public function templates() {
        $templates = FormTemplate::latest()->get();
        return view('shuoun.templates', compact('templates'));
    }

    public function createTemplate() {
        return view('shuoun.template-form');
    }

    public function storeTemplate(Request $request) {
        $request->validate([
            'department'    => 'required',
            'level'         => 'required',
            'academic_year' => 'required',
            'subjects'      => 'required|array|min:1',
        ]);
        FormTemplate::create([
            ...$request->only(['department', 'level', 'academic_year', 'subjects']),
            'created_by' => Auth::id(),
        ]);
        return redirect()->route('shuoun.templates')->with('success', 'تم إنشاء الفورم');
    }

    public function editTemplate($id) {
        $template = FormTemplate::findOrFail($id);
        return view('shuoun.template-form', compact('template'));
    }

    public function updateTemplate(Request $request, $id) {
        $template = FormTemplate::findOrFail($id);
        $template->update(
            $request->only(['department', 'level', 'academic_year', 'subjects', 'is_active'])
        );
        return redirect()->route('shuoun.templates')->with('success', 'تم التعديل');
    }

    // ==================== Receipts ====================

    public function receipts() {
        $receipts = ReceiptNumber::with('usedByUser')->latest()->paginate(50);
        return view('shuoun.receipts', compact('receipts'));
    }

    public function addReceipts(Request $request) {
        $request->validate(['numbers' => 'required|string']);
        $numbers = array_filter(array_map('trim', explode("\n", $request->numbers)));
        $added   = 0;
        $errors  = [];
        foreach ($numbers as $number) {
            if (strlen($number) !== 7 || !is_numeric($number)) {
                $errors[] = "$number (يجب 7 أرقام)";
                continue;
            }
            if (ReceiptNumber::where('receipt_number', $number)->exists()) {
                $errors[] = "$number (مكرر)";
                continue;
            }
            ReceiptNumber::create(['receipt_number' => $number]);
            $added++;
        }
        $message = "تم إضافة $added رقم إيصال";
        if ($errors) $message .= ' | أخطاء: ' . implode(', ', $errors);
        return back()->with('success', $message);
    }

    // ==================== Doctors ====================

    public function doctors() {
        $doctors = User::where('role', 'doctor')
            ->withCount(['assignments as students_count'])
            ->latest()->get();
        return view('shuoun.doctors', compact('doctors'));
    }

    public function createDoctor() {
        return view('shuoun.doctor-form');
    }

    public function storeDoctor(Request $request) {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required'      => 'اسم الدكتور مطلوب',
            'email.required'     => 'البريد الإلكتروني مطلوب',
            'email.unique'       => 'البريد مسجل مسبقاً',
            'password.min'       => 'كلمة المرور 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'doctor',
        ]);

        return redirect()->route('shuoun.doctors')
            ->with('success', 'تم إضافة د. ' . $request->name . ' بنجاح');
    }

    public function deleteDoctor($id) {
        $doctor = User::where('id', $id)->where('role', 'doctor')->firstOrFail();

        $pendingForms = RegistrationForm::where('academic_advisor_name', $doctor->name)
            ->where('status', 'pending_doctor')->count();

        if ($pendingForms > 0) {
            return back()->withErrors([
                'error' => 'لا يمكن حذف الدكتور — لديه ' . $pendingForms . ' طلب في الانتظار'
            ]);
        }

        AdvisorAssignment::where('doctor_id', $id)->delete();
        $doctor->delete();

        return redirect()->route('shuoun.doctors')
            ->with('success', 'تم حذف الدكتور بنجاح');
    }

    // ==================== Assignments ====================

    public function assignments($doctorId) {
        $doctor      = User::where('id', $doctorId)->where('role', 'doctor')->firstOrFail();
        $assignments = AdvisorAssignment::where('doctor_id', $doctorId)->latest()->get();
        return view('shuoun.assignments', compact('doctor', 'assignments'));
    }

    public function saveAssignments(Request $request, $doctorId) {
        $request->validate(['emails' => 'required|string']);

        $doctor  = User::where('id', $doctorId)->where('role', 'doctor')->firstOrFail();
        $emails  = array_filter(array_map('trim', explode("\n", $request->emails)));
        $added   = 0;
        $updated = 0;
        $errors  = [];

        foreach ($emails as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "$email (بريد غير صالح)";
                continue;
            }
            $existing = AdvisorAssignment::where('student_email', $email)->first();
            if ($existing) {
                if ($existing->doctor_id === (int)$doctorId) continue;
                $existing->update(['doctor_id' => $doctorId]);
                $updated++;
            } else {
                AdvisorAssignment::create([
                    'doctor_id'     => $doctorId,
                    'student_email' => $email,
                ]);
                $added++;
            }
        }

        $message = '';
        if ($added)   $message .= "تم إضافة $added بريد. ";
        if ($updated) $message .= "تم تحديث $updated بريد. ";
        if ($errors)  $message .= 'أخطاء: ' . implode(', ', $errors);

        return back()->with('success', $message ?: 'لا تغييرات جديدة');
    }

    public function deleteAssignment($id) {
        AdvisorAssignment::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف التعيين');
    }

    // ==================== Reports ====================

public function reports() {
    // جيب كل الطلبات المعتمدة فقط
    $approvedForms = RegistrationForm::where('status', 'approved')
        ->with('student')->get();

    // ابني مصفوفة المواد ديناميكياً
    $subjectsData = [];

    foreach ($approvedForms as $form) {
        if (!is_array($form->subjects)) continue;

        foreach ($form->subjects as $subject) {
            if (!isset($subject['selected']) || !$subject['selected']) continue;

            $code = $subject['code'] ?? 'UNKNOWN';
            $name = $subject['name'] ?? 'غير معروف';
            $hours = $subject['hours'] ?? 0;

            if (!isset($subjectsData[$code])) {
                $subjectsData[$code] = [
                    'code'     => $code,
                    'name'     => $name,
                    'hours'    => $hours,
                    'students' => [],
                    'count'    => 0,
                ];
            }

            $subjectsData[$code]['students'][] = [
                'name'        => $form->student->name,
                'academic_id' => $form->student->academic_id,
                'email'       => $form->academic_email,
                'department'  => $form->department,
                'level'       => $form->level,
                'approved_at' => $form->approved_at?->format('Y/m/d'),
            ];
            $subjectsData[$code]['count']++;
        }
    }

    // رتّب حسب عدد الطلاب تنازلياً
    uasort($subjectsData, fn($a, $b) => $b['count'] - $a['count']);

    // إحصائيات عامة
    $stats = [
        'total_students'  => RegistrationForm::where('status', 'approved')->count(),
        'total_subjects'  => count($subjectsData),
        'total_doctors'   => \App\Models\User::where('role', 'doctor')->count(),
        'total_forms'     => RegistrationForm::count(),
    ];

    return view('shuoun.reports', compact('subjectsData', 'stats'));
}

public function reportSubjectPdf($code) {
    $approvedForms = RegistrationForm::where('status', 'approved')
        ->with('student')->get();

    $subject  = null;
    $students = [];

    foreach ($approvedForms as $form) {
        if (!is_array($form->subjects)) continue;
        foreach ($form->subjects as $subj) {
            if (!isset($subj['selected']) || !$subj['selected']) continue;
            if (($subj['code'] ?? '') !== $code) continue;

            if (!$subject) {
                $subject = [
                    'code'  => $subj['code'],
                    'name'  => $subj['name'],
                    'hours' => $subj['hours'],
                ];
            }
            $students[] = [
                'name'        => $form->student->name,
                'academic_id' => $form->student->academic_id,
                'email'       => $form->academic_email,
                'department'  => $form->department,
                'level'       => $form->level,
                'approved_at' => $form->approved_at?->format('Y/m/d'),
            ];
        }
    }

    if (!$subject) abort(404, 'المادة غير موجودة');

    $tempDir = storage_path('app/mpdf_temp');
    if (!file_exists($tempDir)) mkdir($tempDir, 0755, true);

    $mpdf = new Mpdf([
        'mode'          => 'utf-8',
        'format'        => 'A4',
        'orientation'   => 'P',
        'margin_top'    => 15,
        'margin_bottom' => 15,
        'margin_left'   => 15,
        'margin_right'  => 15,
        'tempDir'       => $tempDir,
    ]);
    $mpdf->SetDirectionality('rtl');
    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;
    $mpdf->autoArabic       = true;

    $html = view('pdf.report-subject', compact('subject', 'students'))->render();
    $mpdf->WriteHTML($html);
    $mpdf->Output('تقرير_مادة_' . $code . '.pdf', 'D'); // D = download مباشر
}

public function reportAllPdf() {
    $approvedForms = RegistrationForm::where('status', 'approved')
        ->with('student')->get();

    $subjectsData = [];
    foreach ($approvedForms as $form) {
        if (!is_array($form->subjects)) continue;
        foreach ($form->subjects as $subject) {
            if (!isset($subject['selected']) || !$subject['selected']) continue;
            $code = $subject['code'] ?? 'UNKNOWN';
            if (!isset($subjectsData[$code])) {
                $subjectsData[$code] = [
                    'code'     => $code,
                    'name'     => $subject['name'] ?? '',
                    'hours'    => $subject['hours'] ?? 0,
                    'students' => [],
                ];
            }
            $subjectsData[$code]['students'][] = [
                'name'        => $form->student->name,
                'academic_id' => $form->student->academic_id,
                'email'       => $form->academic_email,
                'department'  => $form->department,
                'level'       => $form->level,
                'approved_at' => $form->approved_at?->format('Y/m/d'),
            ];
        }
    }
    uasort($subjectsData, fn($a, $b) => count($b['students']) - count($a['students']));

    $doctors = \App\Models\User::where('role', 'doctor')
        ->withCount(['assignments as students_count'])
        ->get();

    $stats = [
        'total_students' => RegistrationForm::where('status', 'approved')->count(),
        'total_subjects' => count($subjectsData),
        'total_doctors'  => $doctors->count(),
        'generated_at'   => now()->format('Y/m/d H:i'),
    ];

    $tempDir = storage_path('app/mpdf_temp');
    if (!file_exists($tempDir)) mkdir($tempDir, 0755, true);

    $mpdf = new Mpdf([
        'mode'          => 'utf-8',
        'format'        => 'A4',
        'orientation'   => 'P',
        'margin_top'    => 15,
        'margin_bottom' => 15,
        'margin_left'   => 15,
        'margin_right'  => 15,
        'tempDir'       => $tempDir,
    ]);
    $mpdf->SetDirectionality('rtl');
    $mpdf->autoScriptToLang = true;
    $mpdf->autoLangToFont   = true;
    $mpdf->autoArabic       = true;

    $html = view('pdf.report-all', compact('subjectsData', 'doctors', 'stats'))->render();
    $mpdf->WriteHTML($html);
    $mpdf->Output('تقرير_MTIS_الكامل_' . date('Y-m-d') . '.pdf', 'D');
}
}
