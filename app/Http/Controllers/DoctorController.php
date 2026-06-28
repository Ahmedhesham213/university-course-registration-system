<?php
namespace App\Http\Controllers;

use App\Models\RegistrationForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DoctorController extends Controller {

    public function dashboard() {
        $pendingForms = RegistrationForm::where('academic_advisor_name', Auth::user()->name)
            ->where('status', 'pending_doctor')
            ->with('student')->latest()->get();

        $signedForms = RegistrationForm::where('academic_advisor_name', Auth::user()->name)
            ->whereIn('status', ['pending_shuoun', 'approved'])
            ->with('student')->latest()->take(10)->get();

        $hasProfile = Auth::user()->signature_data !== null;

        return view('doctor.dashboard', compact('pendingForms', 'signedForms', 'hasProfile'));
    }

    public function showSetup() {
        return view('doctor.setup');
    }

    public function saveSetup(Request $request) {
        $request->validate([
            'signature_data' => 'required|string',
            'stamp_image'    => 'nullable|image|max:2048',
        ]);

        $data = ['signature_data' => encrypt($request->signature_data)];

        if ($request->hasFile('stamp_image')) {
            $stampPath = $request->file('stamp_image')->store(
                'stamps/' . Auth::id(), 'local' 
            );
            $data['stamp_path'] = $stampPath;
        }

        Auth::user()->update($data);

        return redirect()->route('doctor.dashboard')
            ->with('success', 'تم حفظ التوقيع والختم بنجاح');
    }

    public function showForm($id) {
        $form = RegistrationForm::where('id', $id)
            ->where('academic_advisor_name', Auth::user()->name)
            ->where('status', 'pending_doctor')
            ->with('student')->firstOrFail();

        return view('doctor.form', compact('form'));
    }

    public function signForm(Request $request, $id) {
        $form = RegistrationForm::where('id', $id)
            ->where('academic_advisor_name', Auth::user()->name)
            ->where('status', 'pending_doctor')->firstOrFail();

        $doctor = Auth::user();

        if (!$doctor->signature_data) {
            return back()->withErrors(['error' => 'يجب إعداد التوقيع أولاً من صفحة الإعدادات']);
        }

        $form->update([
            'status'            => 'pending_shuoun',
            'doctor_signature'  => $doctor->signature_data, // already encrypted
            'doctor_stamp_path' => $doctor->stamp_path,
            'doctor_signed_at'  => now(),
        ]);

        return redirect()->route('doctor.dashboard')
            ->with('success', 'تم التوقيع وإرسال الورقة للشؤون');
    }
}