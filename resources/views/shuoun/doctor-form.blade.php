@extends('layouts.app')
@section('title', 'إضافة دكتور جديد')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card p-4">
    <h5 class="fw-bold mb-1">
        <i class="bi bi-person-plus"></i> إضافة دكتور / مرشد أكاديمي جديد
    </h5>
    <p class="text-muted small mb-4">
        سيتم إنشاء حساب للدكتور يتمكن من الدخول به وإعداد توقيعه وختمه
    </p>

    <form method="POST" action="{{ route('shuoun.doctors.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-bold">الاسم الكامل للدكتور</label>
            <input type="text" name="name" class="form-control form-control-lg"
                   value="{{ old('name') }}"
                   placeholder="د. محمد أحمد" required autofocus>
            <div class="form-text">
                <i class="bi bi-info-circle"></i>
                اكتب الاسم بالضبط كما سيظهر على ورقة التسجيل
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">البريد الإلكتروني</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email') }}"
                   placeholder="doctor@mtis.edu.eg" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">كلمة المرور</label>
            <input type="password" name="password" class="form-control"
                   placeholder="8 أحرف على الأقل" required>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">تأكيد كلمة المرور</label>
            <input type="password" name="password_confirmation"
                   class="form-control" required>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary fw-bold px-4">
                <i class="bi bi-person-check"></i> إضافة الدكتور
            </button>
            <a href="{{ route('shuoun.doctors') }}" class="btn btn-outline-secondary">
                إلغاء
            </a>
        </div>
    </form>
</div>
</div>
</div>
@endsection