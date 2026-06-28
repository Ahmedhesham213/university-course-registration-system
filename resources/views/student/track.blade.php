@extends('layouts.app')
@section('title', 'تتبع الطلب')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card p-4">
    <h5 class="fw-bold mb-1"><i class="bi bi-geo-alt"></i> تتبع خط سير الورقة</h5>
    <p class="text-muted small mb-4">طلب رقم #{{ $form->id }} — إيصال: {{ $form->receipt_number }}</p>

    <!-- Timeline -->
    <div class="mb-4">
        @php
            $s = $form->status;
            $step1 = 'done';
            $step2 = match(true) { $s === 'pending_doctor' => 'current', default => 'done' };
            $step3 = match(true) { $s === 'pending_doctor' => 'waiting', $s === 'pending_shuoun' => 'current', default => 'done' };
            $step4 = match(true) { $s === 'approved' => 'done', $s === 'rejected' => 'done', default => 'waiting' };
        @endphp

        <div class="timeline-step">
            <div class="timeline-dot dot-{{ $step1 }}"><i class="bi bi-check2"></i></div>
            <div>
                <div class="fw-bold">تسجيل البيانات والدفع</div>
                <div class="text-muted small">{{ $form->created_at->format('Y/m/d H:i') }}</div>
            </div>
        </div>

        <div class="timeline-step">
            <div class="timeline-dot dot-{{ $step2 }}">
                @if($step2 === 'done') <i class="bi bi-check2"></i>
                @elseif($step2 === 'current') <i class="bi bi-hourglass-split"></i>
                @else <i class="bi bi-circle"></i> @endif
            </div>
            <div>
                <div class="fw-bold">في انتظار توقيع المرشد الأكاديمي</div>
                <div class="text-muted small">{{ $form->academic_advisor_name }}</div>
                @if($form->doctor_signed_at)
                    <div class="text-success small"><i class="bi bi-check-circle"></i> وقّع في {{ $form->doctor_signed_at->format('Y/m/d H:i') }}</div>
                @endif
            </div>
        </div>

        <div class="timeline-step">
            <div class="timeline-dot dot-{{ $step3 }}">
                @if($step3 === 'done') <i class="bi bi-check2"></i>
                @elseif($step3 === 'current') <i class="bi bi-hourglass-split"></i>
                @else <i class="bi bi-circle"></i> @endif
            </div>
            <div>
                <div class="fw-bold">مراجعة الشؤون الطلابية</div>
                @if($s === 'pending_shuoun') <div class="text-info small">الورقة وصلت للشؤون</div> @endif
                @if($form->approved_at) <div class="text-success small">تمت المراجعة {{ $form->approved_at->format('Y/m/d H:i') }}</div> @endif
            </div>
        </div>

        <div class="timeline-step">
            <div class="timeline-dot dot-{{ $step4 }}">
                @if($s === 'approved') <i class="bi bi-trophy"></i>
                @elseif($s === 'rejected') <i class="bi bi-x"></i>
                @else <i class="bi bi-circle"></i> @endif
            </div>
            <div>
                @if($s === 'approved')
                    <div class="fw-bold text-success">🎉 مبروك! تم تسجيل المواد</div>
                    @if($form->pdf_path)
                    <a href="{{ Storage::url($form->pdf_path) }}" target="_blank" class="btn btn-success btn-sm mt-2">
                        <i class="bi bi-file-pdf"></i> تحميل الورقة الرسمية PDF
                    </a>
                    @endif
                @elseif($s === 'rejected')
                    <div class="fw-bold text-danger">تم رفض الطلب</div>
                    <div class="text-muted small">{{ $form->rejection_reason }}</div>
                    <a href="{{ route('student.register-form') }}" class="btn btn-outline-primary btn-sm mt-2">تقديم طلب جديد</a>
                @else
                    <div class="fw-bold text-muted">النتيجة النهائية</div>
                @endif
            </div>
        </div>
    </div>

    <div class="text-center mt-2">
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-right"></i> العودة للرئيسية
        </a>
    </div>
</div>
</div>
</div>
@endsection