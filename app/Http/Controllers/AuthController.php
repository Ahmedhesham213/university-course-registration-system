<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ReceiptNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {

    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'البريد الإلكتروني مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return match(Auth::user()->role) {
                'student' => redirect()->route('student.dashboard'),
                'doctor'  => redirect()->route('doctor.dashboard'),
                'shuoun'  => redirect()->route('shuoun.dashboard'),
                default   => redirect('/'),
            };
        }

        return back()->withErrors(['email' => 'البريد أو كلمة المرور غير صحيحة']);
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8|confirmed',
            'national_id' => 'required|string|size:14',
            'academic_id' => 'required|string|unique:users',
            'department'  => 'required|string',
            'level'       => 'required|string',
        ], [
            'name.required'        => 'الاسم مطلوب',
            'email.required'       => 'البريد الأكاديمي مطلوب',
            'email.unique'         => 'البريد مسجل مسبقاً',
            'password.min'         => 'كلمة المرور 8 أحرف على الأقل',
            'password.confirmed'   => 'كلمة المرور غير متطابقة',
            'national_id.size'     => 'الرقم القومي 14 رقم',
            'academic_id.unique'   => 'الرقم الأكاديمي مسجل مسبقاً',
            'department.required'  => 'القسم مطلوب',
            'level.required'       => 'المستوى مطلوب',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'student',
            'national_id' => $request->national_id,
            'academic_id' => $request->academic_id,
            'department'  => $request->department,
            'level'       => $request->level,
        ]);

        Auth::login($user);
        return redirect()->route('student.dashboard');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}