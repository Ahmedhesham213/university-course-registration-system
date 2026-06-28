<?php
namespace App\Http\Controllers;

use App\Models\RegistrationForm;
use App\Models\FormTemplate;
use App\Models\ReceiptNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller {

    public function dashboard() {
        $forms = RegistrationForm::where('student_id', Auth::id())
            ->latest()->get();
        return view('student.dashboard', compact('forms'));
    }

    public function showRegistrationForm() {
        
        $existingForm = RegistrationForm::where('student_id', Auth::id())
            ->whereIn('status', ['pending_doctor', 'pending_shuoun'])
            ->first();
        if ($existingForm) {
            return redirect()->route('student.track', $existingForm->id)
                ->with('info', 'لديك طلب قيد المراجعة');
        }
        return view('student.register-form');
    }

    public function submitRegistrationForm(Request $request) {
        $request->validate([
            'national_id'          => 'required|size:14',
            'academic_email'       => 'required|email',
            'department'           => 'required',
            'level'                => 'required',
            'receipt_number'       => 'required|size:7',
            'receipt_image'        => 'required|image|max:2048',
            'academic_advisor_name'=> 'required|string',
        ]);

        
        $receipt = ReceiptNumber::where('receipt_number', $request->receipt_number)
            ->where('is_used', false)->first();

        if (!$receipt) {
            return back()->withErrors(['receipt_number' => 'رقم الإيصال غير صالح أو مستخدم من قبل']);
        }

        
        $usedInForm = RegistrationForm::where('receipt_number', $request->receipt_number)->exists();
        if ($usedInForm) {
            return back()->withErrors(['receipt_number' => 'رقم الإيصال مستخدم في طلب آخر']);
        }

        
        $imagePath = $request->file('receipt_image')->store('receipts', 'public');

        
        $form = RegistrationForm::create([
            'student_id'           => Auth::id(),
            'national_id'          => $request->national_id,
            'academic_email'       => $request->academic_email,
            'department'           => $request->department,
            'level'                => $request->level,
            'receipt_number'       => $request->receipt_number,
            'receipt_image_path'   => $imagePath,
            'academic_advisor_name'=> $request->academic_advisor_name,
            'subjects'             => [],
            'student_signature'    => '',
            'status'               => 'pending_doctor',
        ]);

        
        $receipt->update(['is_used' => true, 'used_by' => Auth::id()]);

        return redirect()->route('student.step2', $form->id);
    }

    public function showSubjectForm($formId) {
        $form = RegistrationForm::where('id', $formId)
            ->where('student_id', Auth::id())->firstOrFail();

        $template = FormTemplate::where('department', $form->department)
            ->where('level', $form->level)
            ->where('is_active', true)->first();

        return view('student.subject-form', compact('form', 'template'));
    }

    public function submitSubjectForm(Request $request, $formId) {
        $request->validate([
            'subjects'          => 'required|array|min:1',
            'student_signature' => 'required|string', // base64
        ]);

        $form = RegistrationForm::where('id', $formId)
            ->where('student_id', Auth::id())->firstOrFail();

        $form->update([
            'subjects'          => $request->subjects,
            'student_signature' => encrypt($request->student_signature),
            'status'            => 'pending_doctor',
        ]);

        return redirect()->route('student.track', $form->id)
            ->with('success', 'تم إرسال الطلب للمرشد الأكاديمي');
    }

    public function trackForm($id) {
        $form = RegistrationForm::where('id', $id)
            ->where('student_id', Auth::id())->firstOrFail();
        return view('student.track', compact('form'));
    }

    public function verifyDocument($hash) {
        $form = RegistrationForm::where('unique_hash', $hash)
            ->where('status', 'approved')->first();

        if (!$form) {
            return view('verify', ['valid' => false]);
        }

        return view('verify', [
            'valid'  => true,
            'form'   => $form,
            'student'=> $form->student,
        ]);
    }
}