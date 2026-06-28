@extends('layouts.app')
@section('title', 'فورمات المواد')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h5 class="fw-bold mb-0">فورمات المواد</h5>
    <a href="{{ route('shuoun.templates.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> فورم جديد</a>
</div>
@if($templates->isEmpty())
<div class="card text-center p-5">
    <i class="bi bi-table" style="font-size:3rem;color:#dee2e6;"></i>
    <p class="text-muted mt-3">لا توجد فورمات. أنشئ فورم لكل قسم ومستوى.</p>
</div>
@else
<div class="card p-3">
    <table class="table table-hover">
        <thead class="table-light"><tr><th>القسم</th><th>المستوى</th><th>العام الدراسي</th><th>عدد المواد</th><th>الحالة</th><th></th></tr></thead>
        <tbody>
            @foreach($templates as $t)
            <tr>
                <td>{{ $t->department }}</td>
                <td>{{ $t->level }}</td>
                <td>{{ $t->academic_year }}</td>
                <td>{{ count($t->subjects) }} مادة</td>
                <td><span class="badge {{ $t->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $t->is_active ? 'فعّال' : 'معطّل' }}</span></td>
                <td><a href="{{ route('shuoun.templates.edit', $t->id) }}" class="btn btn-sm btn-outline-primary">تعديل</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection