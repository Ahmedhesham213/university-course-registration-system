<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTIS - إنشاء حساب طالب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1f2e 0%, #2d3561 100%); min-height:100vh; display:flex; align-items:center; padding:2rem 0; }
        .card { border:none; border-radius:16px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="text-center mb-4">
                <div style="color:#4f8ef7;font-size:1.8rem;font-weight:700;">🎓 MTIS</div>
            </div>
            <div class="card p-4">
                <h5 class="mb-4 fw-bold text-center">تسجيل طالب جديد</h5>
                @if($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                @endif
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الاسم الكامل</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الرقم الأكاديمي</label>
                            <input type="text" name="academic_id" class="form-control" value="{{ old('academic_id') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">البريد الأكاديمي</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">الرقم القومي (14 رقم)</label>
                            <input type="text" name="national_id" class="form-control" maxlength="14" value="{{ old('national_id') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">القسم</label>
                            <select name="department" class="form-select" required>
                                <option value="">اختر القسم</option>
                                <option value="information_systems" {{ old('department')=='information_systems'?'selected':'' }}>نظم المعلومات</option>
                                <option value="business_administration" {{ old('department')=='business_administration'?'selected':'' }}>إدارة الأعمال</option>
                                <option value="accounting" {{ old('department')=='accounting'?'selected':'' }}>محاسبة</option>
                                <option value="marketing" {{ old('department')=='marketing'?'selected':'' }}>تسويق</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">المستوى الدراسي</label>
                            <select name="level" class="form-select" required>
                                <option value="">اختر المستوى</option>
                                <option value="1" {{ old('level')=='1'?'selected':'' }}>المستوى الأول</option>
                                <option value="2" {{ old('level')=='2'?'selected':'' }}>المستوى الثاني</option>
                                <option value="3" {{ old('level')=='3'?'selected':'' }}>المستوى الثالث</option>
                                <option value="4" {{ old('level')=='4'?'selected':'' }}>المستوى الرابع</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">كلمة المرور</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mt-4">إنشاء الحساب</button>
                </form>
                <p class="text-center mt-3 mb-0 small">لديك حساب؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>