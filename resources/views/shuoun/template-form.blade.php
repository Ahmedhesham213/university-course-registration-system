@extends('layouts.app')
@section('title', isset($template) ? 'تعديل فورم' : 'فورم جديد')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card p-4">
    <h5 class="fw-bold mb-4">{{ isset($template) ? 'تعديل فورم المواد' : 'إنشاء فورم مواد جديد' }}</h5>

    <form method="POST" action="{{ isset($template) ? route('shuoun.templates.update', $template->id) : route('shuoun.templates.store') }}">
        @csrf
        @if(isset($template)) @method('PUT') @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold">القسم</label>
                <select name="department" class="form-select" required>
                    <option value="">اختر</option>
                    <option value="information_systems" {{ ($template->department??'')==='information_systems'?'selected':'' }}>نظم المعلومات</option>
                    <option value="business_administration" {{ ($template->department??'')==='business_administration'?'selected':'' }}>إدارة الأعمال</option>
                    <option value="accounting" {{ ($template->department??'')==='accounting'?'selected':'' }}>محاسبة</option>
                    <option value="marketing" {{ ($template->department??'')==='marketing'?'selected':'' }}>تسويق</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">المستوى</label>
                <select name="level" class="form-select" required>
                    <option value="">اختر</option>
                    @foreach([1,2,3,4] as $l)
                    <option value="{{ $l }}" {{ ($template->level??'')==$l?'selected':'' }}>المستوى {{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">العام الدراسي</label>
                <input type="text" name="academic_year" class="form-control" value="{{ $template->academic_year ?? '2024/2025' }}" required>
            </div>
        </div>

        @if(isset($template))
        <div class="mb-3">
            <label class="form-label fw-bold">حالة الفورم</label>
            <select name="is_active" class="form-select" style="max-width:200px;">
                <option value="1" {{ $template->is_active ? 'selected' : '' }}>فعّال</option>
                <option value="0" {{ !$template->is_active ? 'selected' : '' }}>معطّل</option>
            </select>
        </div>
        @endif

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-bold mb-0">المواد الدراسية</label>
                <button type="button" onclick="addSubject()" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus"></i> إضافة مادة
                </button>
            </div>
            <div id="subjects-container">
                @if(isset($template) && $template->subjects)
                    @foreach($template->subjects as $i => $subj)
                    <div class="row g-2 mb-2 subject-row">
                        <div class="col-md-5"><input type="text" name="subjects[{{ $i }}][name]" class="form-control" placeholder="اسم المادة" value="{{ $subj['name'] }}" required></div>
                        <div class="col-md-3"><input type="text" name="subjects[{{ $i }}][code]" class="form-control" placeholder="الكود" value="{{ $subj['code'] }}" required></div>
                        <div class="col-md-2"><input type="number" name="subjects[{{ $i }}][hours]" class="form-control" placeholder="الساعات" value="{{ $subj['hours'] }}" min="1" max="6" required></div>
                        <div class="col-md-2"><button type="button" onclick="this.closest('.subject-row').remove()" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i></button></div>
                    </div>
                    @endforeach
                @else
                    <!-- صف فارغ أول -->
                    <div class="row g-2 mb-2 subject-row">
                        <div class="col-md-5"><input type="text" name="subjects[0][name]" class="form-control" placeholder="اسم المادة" required></div>
                        <div class="col-md-3"><input type="text" name="subjects[0][code]" class="form-control" placeholder="الكود" required></div>
                        <div class="col-md-2"><input type="number" name="subjects[0][hours]" class="form-control" placeholder="ساعات" min="1" max="6" required></div>
                        <div class="col-md-2"><button type="button" onclick="this.closest('.subject-row').remove()" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i></button></div>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary fw-bold px-4">حفظ الفورم</button>
            <a href="{{ route('shuoun.templates') }}" class="btn btn-outline-secondary">إلغاء</a>
        </div>
    </form>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
let idx = {{ isset($template) ? count($template->subjects) : 1 }};
function addSubject() {
    document.getElementById('subjects-container').insertAdjacentHTML('beforeend', `
        <div class="row g-2 mb-2 subject-row">
            <div class="col-md-5"><input type="text" name="subjects[${idx}][name]" class="form-control" placeholder="اسم المادة" required></div>
            <div class="col-md-3"><input type="text" name="subjects[${idx}][code]" class="form-control" placeholder="الكود" required></div>
            <div class="col-md-2"><input type="number" name="subjects[${idx}][hours]" class="form-control" placeholder="ساعات" min="1" max="6" required></div>
            <div class="col-md-2"><button type="button" onclick="this.closest('.subject-row').remove()" class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i></button></div>
        </div>`);
    idx++;
}
</script>
@endpush