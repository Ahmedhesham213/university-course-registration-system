<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MTIS - @yield('title', 'نظام تسجيل المواد')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; font-family:'Segoe UI',Tahoma,sans-serif; }
        .navbar-brand { font-weight:700; font-size:1.4rem; }
        .sidebar { min-height:calc(100vh - 56px); background:#1a1f2e; width:220px; flex-shrink:0; }
        .sidebar .nav-link { color:#adb5bd; padding:.75rem 1rem; border-radius:8px; margin:2px 8px; transition:all .2s; }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active { background:rgba(255,255,255,.1); color:#fff; }
        .sidebar .nav-link i { width:20px; }
        .sidebar .nav-section { color:#6c757d; font-size:10px; text-transform:uppercase;
            letter-spacing:1px; padding:.5rem 1rem; margin-top:.5rem; }
        .card { border:none; border-radius:12px; box-shadow:0 2px 12px rgba(0,0,0,.06); }
        .btn { border-radius:8px; }
        .badge-status-pending_doctor { background:#fff3cd; color:#856404; }
        .badge-status-pending_shuoun { background:#cff4fc; color:#055160; }
        .badge-status-approved       { background:#d1e7dd; color:#0a3622; }
        .badge-status-rejected       { background:#f8d7da; color:#58151c; }
        .timeline-step { display:flex; gap:16px; margin-bottom:24px; }
        .timeline-dot  { width:36px; height:36px; border-radius:50%; display:flex;
            align-items:center; justify-content:center; flex-shrink:0; font-size:.8rem; }
        .dot-done    { background:#d1e7dd; color:#0a3622; }
        .dot-current { background:#0d6efd; color:#fff; }
        .dot-waiting { background:#e9ecef; color:#6c757d; }
        @media print { .no-print { display:none!important; } }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-dark bg-dark no-print">
    <div class="container-fluid">
        <span class="navbar-brand">🎓 MTIS</span>
        @auth
        <div class="d-flex align-items-center gap-3">
            <span class="text-white-50 small">
                @if(auth()->user()->isDoctor()) د. @endif
                {{ auth()->user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-secondary text-white">
                    <i class="bi bi-box-arrow-left"></i> خروج
                </button>
            </form>
        </div>
        @endauth
    </div>
</nav>

<div class="d-flex">
    @auth
    <div class="sidebar no-print">
        <nav class="nav flex-column pt-3">

            @if(auth()->user()->isStudent())
                <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}"
                   href="{{ route('student.dashboard') }}">
                    <i class="bi bi-house"></i> الرئيسية
                </a>
                <a class="nav-link {{ request()->routeIs('student.register-form') ? 'active' : '' }}"
                   href="{{ route('student.register-form') }}">
                    <i class="bi bi-file-plus"></i> طلب جديد
                </a>

            @elseif(auth()->user()->isDoctor())
                <a class="nav-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}"
                   href="{{ route('doctor.dashboard') }}">
                    <i class="bi bi-house"></i> الرئيسية
                </a>
                <a class="nav-link {{ request()->routeIs('doctor.setup') ? 'active' : '' }}"
                   href="{{ route('doctor.setup') }}">
                    <i class="bi bi-pen"></i> التوقيع والختم
                </a>

            @elseif(auth()->user()->isShuoun())
                <a class="nav-link {{ request()->routeIs('shuoun.dashboard') ? 'active' : '' }}"
                   href="{{ route('shuoun.dashboard') }}">
                    <i class="bi bi-house"></i> الرئيسية
                </a>

                <div class="nav-section">الطلبات</div>
                <a class="nav-link {{ request()->routeIs('shuoun.receipts*') ? 'active' : '' }}"
                   href="{{ route('shuoun.receipts') }}">
                    <i class="bi bi-receipt"></i> أرقام الإيصالات
                </a>

                <div class="nav-section">الإعدادات</div>
                <a class="nav-link {{ request()->routeIs('shuoun.doctors*') ? 'active' : '' }}"
                   href="{{ route('shuoun.doctors') }}">
                    <i class="bi bi-person-badge"></i> الدكاترة
                </a>

                <a class="nav-link {{ request()->routeIs('shuoun.templates*') ? 'active' : '' }}"
                   href="{{ route('shuoun.templates') }}">
                    <i class="bi bi-table"></i> فورمات المواد
                </a>

                <a class="nav-link {{ request()->routeIs('shuoun.reports*') ? 'active' : '' }}"
                   href="{{ route('shuoun.reports') }}">
                    <i class="bi bi-bar-chart-line"></i> تقارير
                </a>
            @endif

        </nav>
    </div>
    @endauth

    <div class="flex-grow-1 p-4" style="min-width:0;">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            <i class="bi bi-info-circle"></i> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
