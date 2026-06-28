@extends('layouts.app')
@section('title', 'داشبورد الشؤون')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <div style="font-size:2rem;color:#ffc107;">{{ $stats['pending'] }}</div>
            <div class="text-muted small">في انتظار المراجعة</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <div style="font-size:2rem;color:#198754;">{{ $stats['approved'] }}</div>
            <div class="text-muted small">تم الاعتماد</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center">
            <div style="font-size:2rem;color:#0d6efd;">{{ $stats['total'] }}</div>
            <div class="text-muted small">إجمالي الطلبات</div>
        </div>
    </div>
</div>

@if($pending->isNotEmpty())
<div class="card p-3 mb-4">
    <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-clock"></i> طلبات تنتظر الموافقة ({{ $pending->count() }})</h6>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr><th>الطالب</th><th>القسم</th><th>المستوى</th><th>المرشد</th><th>التاريخ</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($pending as $form)
                <tr>
                    <td>{{ $form->student->name }}</td>
                    <td>{{ $form->department }}</td>
                    <td>{{ $form->level }}</td>
                    <td>{{ $form->academic_advisor_name }}</td>
                    <td class="small text-muted">{{ $form->created_at->format('Y/m/d') }}</td>
                    <td><a href="{{ route('shuoun.form', $form->id) }}" class="btn btn-warning btn-sm">مراجعة</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($approved->isNotEmpty())
<div class="card p-3">
    <h6 class="fw-bold mb-3 text-success"><i class="bi bi-check-circle"></i> آخر الطلبات المعتمدة</h6>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead class="table-light">
                <tr><th>الطالب</th><th>القسم</th><th>تاريخ الاعتماد</th><th>PDF</th></tr>
            </thead>
            <tbody>
                @foreach($approved as $form)
                <tr>
                    <td>{{ $form->student->name }}</td>
                    <td>{{ $form->department }}</td>
                    <td>{{ $form->approved_at?->format('Y/m/d') }}</td>
                    <td>
                        @if($form->pdf_path)
                        <a href="{{ Storage::url($form->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection