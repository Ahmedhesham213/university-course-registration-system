@extends('layouts.app')
@section('title', 'طلب تسجيل جديد')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card p-4">
    <h5 class="fw-bold mb-1"><i class="bi bi-file-plus"></i> طلب تسجيل المواد</h5>
    <p class="text-muted small mb-4">الخطوة 1 من 2 — بيانات الدفع والتحقق</p>

    <form method="POST" action="{{ route('student.register-form.submit') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-bold">الرقم القومي</label>
                <input type="text" name="national_id" class="form-control" maxlength="14"
                       value="{{ old('national_id', auth()->user()->national_id) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">
                    البريد الأكاديمي
                </label>
                <input type="email" name="academic_email" id="academic_email"
                       class="form-control"
                       value="{{ old('academic_email', auth()->user()->email) }}"
                       required autocomplete="off">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">القسم</label>
                <select name="department" class="form-select" required>
                    <option value="">اختر</option>
                    <option value="information_systems"
                        {{ old('department', auth()->user()->department) == 'information_systems' ? 'selected' : '' }}>
                        نظم المعلومات
                    </option>
                    <option value="business_administration"
                        {{ old('department', auth()->user()->department) == 'business_administration' ? 'selected' : '' }}>
                        إدارة الأعمال
                    </option>
                    <option value="accounting"
                        {{ old('department', auth()->user()->department) == 'accounting' ? 'selected' : '' }}>
                        محاسبة
                    </option>
                    <option value="marketing"
                        {{ old('department', auth()->user()->department) == 'marketing' ? 'selected' : '' }}>
                        تسويق
                    </option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">المستوى</label>
                <select name="level" class="form-select" required>
                    <option value="">اختر</option>
                    <option value="1" {{ old('level', auth()->user()->level) == '1' ? 'selected' : '' }}>الأول</option>
                    <option value="2" {{ old('level', auth()->user()->level) == '2' ? 'selected' : '' }}>الثاني</option>
                    <option value="3" {{ old('level', auth()->user()->level) == '3' ? 'selected' : '' }}>الثالث</option>
                    <option value="4" {{ old('level', auth()->user()->level) == '4' ? 'selected' : '' }}>الرابع</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">رقم إيصال الدفع (7 أرقام)</label>
                <input type="text" name="receipt_number" class="form-control"
                       maxlength="7" value="{{ old('receipt_number') }}" required>
                <div class="form-text">الرقم الموجود على إيصال دفع الرسوم</div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">
                    اسم المرشد الأكاديمي
                    <span id="advisor-loading" class="text-muted small d-none">
                        <span class="spinner-border spinner-border-sm"></span> جاري البحث...
                    </span>
                    <span id="advisor-found" class="text-success small d-none">
                        <i class="bi bi-check-circle-fill"></i> تم التعرف تلقائياً
                    </span>
                </label>
                <input type="text" name="academic_advisor_name" id="academic_advisor_name"
                       class="form-control" value="{{ old('academic_advisor_name') }}"
                       placeholder="يُكتب تلقائياً أو اكتبه يدوياً" required>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">صورة إيصال الدفع</label>
                <input type="file" name="receipt_image" class="form-control"
                       accept="image/*" required>
                <div class="form-text">صورة واضحة للإيصال (JPG, PNG — بحد أقصى 2MB)</div>
            </div>

        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">رجوع</a>
            <button type="submit" class="btn btn-primary px-4 fw-bold">
                التالي — اختيار المواد <i class="bi bi-arrow-left"></i>
            </button>
        </div>
    </form>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
const emailInput   = document.getElementById('academic_email');
const advisorInput = document.getElementById('academic_advisor_name');
const loadingEl    = document.getElementById('advisor-loading');
const foundEl      = document.getElementById('advisor-found');
let debounceTimer;

emailInput.addEventListener('input', function () {
    const email = this.value.trim();
    clearTimeout(debounceTimer);
    loadingEl.classList.add('d-none');
    foundEl.classList.add('d-none');
    advisorInput.readOnly = false;
    advisorInput.classList.remove('bg-success-subtle', 'border-success');

    if (!email.includes('@') || email.length < 5) return;

    debounceTimer = setTimeout(() => {
        loadingEl.classList.remove('d-none');
        fetch(`/api/advisor-by-email?email=${encodeURIComponent(email)}`)
            .then(r => r.json())
            .then(data => {
                loadingEl.classList.add('d-none');
                if (data.found) {
                    advisorInput.value    = data.doctor_name;
                    advisorInput.readOnly = true;
                    advisorInput.classList.add('bg-success-subtle', 'border-success');
                    foundEl.classList.remove('d-none');
                } else {
                    advisorInput.readOnly = false;
                    advisorInput.classList.remove('bg-success-subtle', 'border-success');
                }
            })
            .catch(() => loadingEl.classList.add('d-none'));
    }, 600);
});
</script>
@endpush