@extends('layouts.app')
@section('title', 'داشبورد الطالب')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">مرحباً، {{ auth()->user()->name }} 👋</h4>
    <a href="{{ route('student.register-form') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> طلب تسجيل جديد</a>
</div>

@if($forms->isEmpty())
<div class="card text-center p-5">
    <i class="bi bi-file-earmark-plus" style="font-size:4rem; color:#dee2e6;"></i>
    <h5 class="mt-3 text-muted">لا يوجد طلبات سابقة</h5>
    <p class="text-muted">ابدأ بتقديم طلب تسجيل المواد</p>
    <a href="{{ route('student.register-form') }}" class="btn btn-primary mx-auto" style="width:fit-content">تقديم طلب الآن</a>
</div>
@else
<div class="row g-3">
    @foreach($forms as $form)
    <div class="col-12">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold">طلب رقم #{{ $form->id }}</span>
                    <span class="ms-3 badge badge-status-{{ $form->status }}">{{ $form->status_label }}</span>
                    <div class="text-muted small mt-1">{{ $form->created_at->format('Y/m/d') }} — إيصال: {{ $form->receipt_number }}</div>
                </div>
                <a href="{{ route('student.track', $form->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-eye"></i> تتبع الورقة
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection