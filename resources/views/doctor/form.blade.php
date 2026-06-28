@extends('layouts.app')
@section('title', 'مراجعة طلب التسجيل')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card p-4">
    <div class="d-flex justify-content-between mb-4">
        <div>
            <h5 class="fw-bold mb-1">ورقة تسجيل المواد</h5>
            <p class="text-muted small mb-0">طالب: <strong>{{ $form->student->name }}</strong> — رقم أكاديمي: {{ $form->student->academic_id }}</p>
        </div>
        <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary btn-sm">رجوع</a>
    </div>

    <!-- بيانات الطالب -->
    <div class="row g-2 mb-4 p-3 bg-light rounded">
        <div class="col-md-4"><strong>القسم:</strong> {{ $form->department }}</div>
        <div class="col-md-4"><strong>المستوى:</strong> {{ $form->level }}</div>
        <div class="col-md-4"><strong>الرقم القومي:</strong> {{ $form->national_id }}</div>
        <div class="col-md-4"><strong>البريد:</strong> {{ $form->academic_email }}</div>
        <div class="col-md-4"><strong>رقم الإيصال:</strong> {{ $form->receipt_number }}</div>
        <div class="col-12">
            <strong>صورة الإيصال:</strong>
            <a href="{{ Storage::url($form->receipt_image_path) }}" target="_blank" class="btn btn-sm btn-outline-info ms-2">
                <i class="bi bi-image"></i> عرض الإيصال
            </a>
        </div>
    </div>

    <!-- المواد المختارة -->
    <h6 class="fw-bold mb-2">المواد المسجلة</h6>
    <table class="table table-bordered mb-4">
        <thead class="table-light">
            <tr><th>#</th><th>المادة</th><th>الكود</th><th>الساعات</th></tr>
        </thead>
        <tbody>
            @foreach($form->subjects as $i => $subject)
            @if(isset($subject['selected']))
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $subject['name'] }}</td>
                <td><code>{{ $subject['code'] }}</code></td>
                <td>{{ $subject['hours'] }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <!-- توقيع الطالب -->
    <div class="mb-4">
        <h6 class="fw-bold">توقيع الطالب</h6>
        <div style="border:1px solid #dee2e6; border-radius:8px; padding:8px; background:#fff; max-width:400px;">
            <img src="{{ decrypt($form->student_signature) }}" style="width:100%; max-height:100px; object-fit:contain;">
        </div>
    </div>

    <!-- زر التوقيع -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        بالضغط على "توقيع وإرسال للشؤون" سيتم وضع توقيعك وختمك المحفوظين على الورقة تلقائياً وإرسالها للشؤون.
    </div>

    <form method="POST" action="{{ route('doctor.form.sign', $form->id) }}">
        @csrf
        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-success fw-bold px-4">
                <i class="bi bi-pen"></i> توقيع وإرسال للشؤون
            </button>
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary">لاحقاً</a>
        </div>
    </form>
</div>
</div>
</div>
@endsection