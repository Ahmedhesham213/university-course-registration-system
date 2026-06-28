@extends('layouts.app')
@section('title', 'تقارير المواد')
@section('content')

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1"><i class="bi bi-bar-chart-line"></i> تقارير المواد الدراسية</h5>
        <p class="text-muted small mb-0">إحصائيات تلقائية بناءً على الطلبات المعتمدة فعلياً</p>
    </div>
    <a href="{{ route('shuoun.reports.all.pdf') }}" class="btn btn-danger fw-bold">
        <i class="bi bi-file-pdf"></i> تحميل التقرير الكامل PDF
    </a>
</div>

{{-- إحصائيات سريعة --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 text-center h-100">
            <div style="font-size:2rem; color:#0d6efd; font-weight:700;">{{ $stats['total_students'] }}</div>
            <div class="text-muted small mt-1"><i class="bi bi-people"></i> طالب مسجّل</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center h-100">
            <div style="font-size:2rem; color:#198754; font-weight:700;">{{ $stats['total_subjects'] }}</div>
            <div class="text-muted small mt-1"><i class="bi bi-book"></i> مادة مسجّلة</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center h-100">
            <div style="font-size:2rem; color:#6f42c1; font-weight:700;">{{ $stats['total_doctors'] }}</div>
            <div class="text-muted small mt-1"><i class="bi bi-person-badge"></i> مرشد أكاديمي</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 text-center h-100">
            <div style="font-size:2rem; color:#fd7e14; font-weight:700;">{{ $stats['total_forms'] }}</div>
            <div class="text-muted small mt-1"><i class="bi bi-file-text"></i> إجمالي الطلبات</div>
        </div>
    </div>
</div>

{{-- لو مفيش بيانات --}}
@if(empty($subjectsData))
<div class="card text-center p-5">
    <i class="bi bi-inbox" style="font-size:3rem; color:#dee2e6;"></i>
    <p class="text-muted mt-3">لا توجد طلبات معتمدة بعد — التقارير تظهر بعد اعتماد أول طلب</p>
</div>
@else

{{-- بحث سريع --}}
<div class="mb-3">
    <input type="text" id="subject-search" class="form-control"
           placeholder="🔍 ابحث عن مادة بالاسم أو الكود...">
</div>

{{-- جداول المواد --}}
<div id="subjects-container">
@foreach($subjectsData as $code => $subject)
<div class="card mb-4 subject-card" data-name="{{ $subject['name'] }}" data-code="{{ $subject['code'] }}">
    {{-- Header المادة --}}
    <div class="card-header d-flex justify-content-between align-items-center py-3"
         style="background:#1a1f2e; border-radius:12px 12px 0 0;">
        <div>
            <span class="fw-bold text-white fs-6">{{ $subject['name'] }}</span>
            <span class="badge bg-secondary ms-2">{{ $subject['code'] }}</span>
            <span class="badge bg-info text-dark ms-1">{{ $subject['hours'] }} ساعات</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge rounded-pill fs-6 px-3 py-2"
                  style="background:#4f8ef7;">
                {{ $subject['count'] }} طالب
            </span>
            <a href="{{ route('shuoun.reports.subject.pdf', $code) }}"
               class="btn btn-sm btn-outline-light">
                <i class="bi bi-file-pdf"></i> PDF
            </a>
            {{-- زر إخفاء/إظهار الجدول --}}
            <button class="btn btn-sm btn-outline-light toggle-btn"
                    data-target="table-{{ $loop->index }}">
                <i class="bi bi-chevron-up"></i>
            </button>
        </div>
    </div>

    {{-- جدول الطلاب --}}
    <div class="card-body p-0" id="table-{{ $loop->index }}">
        @if(count($subject['students']) > 0)
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="40">#</th>
                        <th>اسم الطالب</th>
                        <th>الرقم الأكاديمي</th>
                        <th>البريد الأكاديمي</th>
                        <th>القسم</th>
                        <th>المستوى</th>
                        <th>تاريخ التسجيل</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subject['students'] as $i => $student)
                    <tr>
                        <td class="text-muted small">{{ $i + 1 }}</td>
                        <td class="fw-bold">{{ $student['name'] }}</td>
                        <td><code>{{ $student['academic_id'] }}</code></td>
                        <td class="small text-muted">{{ $student['email'] }}</td>
                        <td>
                            @php
                            $depts = [
                                'information_systems'     => 'نظم المعلومات',
                                'business_administration' => 'إدارة الأعمال',
                                'accounting'              => 'محاسبة',
                                'marketing'               => 'تسويق',
                            ];
                            @endphp
                            {{ $depts[$student['department']] ?? $student['department'] }}
                        </td>
                        <td>المستوى {{ $student['level'] }}</td>
                        <td class="small text-muted">{{ $student['approved_at'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="7" class="text-end small text-muted py-2 px-3">
                            إجمالي: <strong>{{ count($subject['students']) }} طالب</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="text-center text-muted py-3 small">لا يوجد طلاب مسجلون في هذه المادة بعد</div>
        @endif
    </div>
</div>
@endforeach
</div>

{{-- زر التقرير الكامل في الأسفل --}}
<div class="text-center mt-4 mb-2">
    <a href="{{ route('shuoun.reports.all.pdf') }}" class="btn btn-danger btn-lg fw-bold px-5">
        <i class="bi bi-file-earmark-pdf"></i>
        تحميل التقرير الكامل — كل المواد والطلاب والدكاترة
    </a>
</div>

@endif

@endsection

@push('scripts')
<script>
// بحث في المواد
document.getElementById('subject-search').addEventListener('input', function() {
    const q = this.value.trim().toLowerCase();
    document.querySelectorAll('.subject-card').forEach(card => {
        const name = card.dataset.name.toLowerCase();
        const code = card.dataset.code.toLowerCase();
        card.style.display = (name.includes(q) || code.includes(q)) ? '' : 'none';
    });
});

// إخفاء/إظهار جدول المادة
document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetId = this.dataset.target;
        const target   = document.getElementById(targetId);
        const icon     = this.querySelector('i');
        if (target.style.display === 'none') {
            target.style.display = '';
            icon.className = 'bi bi-chevron-up';
        } else {
            target.style.display = 'none';
            icon.className = 'bi bi-chevron-down';
        }
    });
});
</script>
@endpush
