@extends('layouts.app')
@section('title', 'داشبورد المرشد الأكاديمي')
@section('content')

@if(!$hasProfile)
<div class="alert alert-warning d-flex justify-content-between align-items-center">
    <span><i class="bi bi-exclamation-triangle"></i> لم تقم بإعداد توقيعك وختمك بعد!</span>
    <a href="{{ route('doctor.setup') }}" class="btn btn-warning btn-sm fw-bold">إعداد الآن</a>
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <div style="font-size:2rem; color:#ffc107;">{{ $pendingForms->count() }}</div>
            <div class="text-muted small">طلبات في الانتظار</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <div style="font-size:2rem; color:#198754;">{{ $signedForms->count() }}</div>
            <div class="text-muted small">طلبات موقّعة</div>
        </div>
    </div>
</div>

@if($pendingForms->isNotEmpty())
<div class="card p-3 mb-4">
    <h6 class="fw-bold mb-3"><i class="bi bi-clock text-warning"></i> طلبات تنتظر توقيعك</h6>
    @foreach($pendingForms as $form)
    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
        <div>
            <span class="fw-bold">{{ $form->student->name }}</span>
            <span class="text-muted small ms-2">{{ $form->department }} / المستوى {{ $form->level }}</span>
            <div class="text-muted small">{{ $form->created_at->diffForHumans() }}</div>
        </div>
        <a href="{{ route('doctor.form', $form->id) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pen"></i> مراجعة وتوقيع
        </a>
    </div>
    @endforeach
</div>
@endif

@if($signedForms->isNotEmpty())
<div class="card p-3">
    <h6 class="fw-bold mb-3"><i class="bi bi-check-circle text-success"></i> آخر الطلبات الموقّعة</h6>
    @foreach($signedForms as $form)
    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
        <div>
            <span class="fw-bold">{{ $form->student->name }}</span>
            <span class="badge badge-status-{{ $form->status }} ms-2">{{ $form->status_label }}</span>
        </div>
        <span class="text-muted small">{{ $form->doctor_signed_at?->format('Y/m/d') }}</span>
    </div>
    @endforeach
</div>
@endif
@endsection