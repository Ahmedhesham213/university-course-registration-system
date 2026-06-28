@extends('layouts.app')
@section('title', 'مراجعة الطلب')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card p-4">
    <div class="d-flex justify-content-between mb-4">
        <h5 class="fw-bold mb-0">مراجعة طلب #{{ $form->id }}</h5>
        <a href="{{ route('shuoun.dashboard') }}" class="btn btn-outline-secondary btn-sm">رجوع</a>
    </div>

    <div class="row g-2 mb-4 p-3 bg-light rounded">
        <div class="col-md-4"><strong>الطالب:</strong> {{ $form->student->name }}</div>
        <div class="col-md-4"><strong>الرقم الأكاديمي:</strong> {{ $form->student->academic_id }}</div>
        <div class="col-md-4"><strong>الرقم القومي:</strong> {{ $form->national_id }}</div>
        <div class="col-md-4"><strong>القسم:</strong> {{ $form->department }}</div>
        <div class="col-md-4"><strong>المستوى:</strong> {{ $form->level }}</div>
        <div class="col-md-4"><strong>رقم الإيصال:</strong> {{ $form->receipt_number }}</div>
        <div class="col-md-6"><strong>المرشد الأكاديمي:</strong> {{ $form->academic_advisor_name }}</div>
        <div class="col-md-6">
            <strong>صورة الإيصال:</strong>
            <a href="{{ Storage::url($form->receipt_image_path) }}" target="_blank" class="btn btn-sm btn-outline-info ms-2">عرض</a>
        </div>
        <div class="col-md-6">
            <strong>وقّع الدكتور في:</strong>
            {{ $form->doctor_signed_at?->format('Y/m/d H:i') ?? 'لم يوقع بعد' }}
        </div>
    </div>

    <h6 class="fw-bold mb-2">المواد المسجلة</h6>
    <table class="table table-bordered mb-4">
        <thead class="table-light"><tr><th>#</th><th>المادة</th><th>الكود</th><th>الساعات</th></tr></thead>
        <tbody>
            @php $i=1; @endphp
            @foreach($form->subjects as $subject)
            @if(isset($subject['selected']))
            <tr><td>{{ $i++ }}</td><td>{{ $subject['name'] }}</td><td><code>{{ $subject['code'] }}</code></td><td>{{ $subject['hours'] }}</td></tr>
            @endif
            @endforeach
        </tbody>
    </table>

    @if($form->status === 'pending_shuoun')
    <div class="row g-3 mt-2">
        <div class="col-md-6">
            <form method="POST" action="{{ route('shuoun.form.approve', $form->id) }}">
                @csrf
                <button type="submit" class="btn btn-success fw-bold w-100"
                    onclick="return confirm('تأكيد اعتماد الطلب وتوليد PDF؟')">
                    <i class="bi bi-check-circle"></i> اعتماد وتوليد الورقة الرسمية
                </button>
            </form>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-danger fw-bold w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="bi bi-x-circle"></i> رفض الطلب
            </button>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">سبب الرفض</h5></div>
                <form method="POST" action="{{ route('shuoun.form.reject', $form->id) }}">
                    @csrf
                    <div class="modal-body">
                        <textarea name="reason" class="form-control" rows="4" placeholder="اكتب سبب الرفض..." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">تأكيد الرفض</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
</div>
</div>
@endsection