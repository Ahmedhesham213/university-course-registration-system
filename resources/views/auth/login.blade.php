<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTIS - تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1a1f2e 0%, #2d3561 100%); min-height: 100vh; display:flex; align-items:center; }
        .card { border:none; border-radius:16px; box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        .brand { color:#4f8ef7; font-size:2rem; font-weight:700; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <div class="brand">🎓 MTIS</div>
                <p class="text-white-50">Management Technology & Information Systems</p>
            </div>
            <div class="card p-4">
                <h5 class="mb-4 text-center fw-bold">تسجيل الدخول</h5>
                @if($errors->any())
                    <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">دخول</button>
                </form>
                <hr>
                <p class="text-center mb-0 small">طالب جديد؟ <a href="{{ route('register') }}">إنشاء حساب</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>