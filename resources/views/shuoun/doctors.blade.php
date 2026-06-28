@extends('layouts.app')
@section('title', 'إدارة الدكاترة')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0"><i class="bi bi-person-badge"></i> الدكاترة والمرشدون الأكاديميون</h5>
    <a href="{{ route('shuoun.doctors.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> إضافة دكتور جديد
    </a>
</div>

@if($doctors->isEmpty())
<div class="card text-center p-5">
    <i class="bi bi-person-x" style="font-size:3rem; color:#dee2e6;"></i>
    <p class="text-muted mt-3">لا يوجد دكاترة مسجلون بعد</p>
    <a href="{{ route('shuoun.doctors.create') }}" class="btn btn-primary mx-auto" style="width:fit-content">
        إضافة أول دكتور
    </a>
</div>
@else
<div class="card p-0 overflow-hidden">
    <table class="table table-hover mb-0">
        <thead class="table-dark">
            <tr>
                <th>اسم الدكتور</th>
                <th>البريد الإلكتروني</th>
                <th class="text-center">الطلاب المسندون</th>
                <th class="text-center">التوقيع والختم</th>
                <th class="text-center">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctors as $doctor)
            <tr>
                <td class="fw-bold align-middle">{{ $doctor->name }}</td>
                <td class="text-muted small align-middle">{{ $doctor->email }}</td>
                <td class="text-center align-middle">
                    <span class="badge bg-primary rounded-pill">
                        {{ $doctor->students_count }} طالب
                    </span>
                </td>
                <td class="text-center align-middle">
                    @if($doctor->signature_data)
                        <span class="badge bg-success">
                            <i class="bi bi-check-lg"></i> مكتمل
                        </span>
                    @else
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-exclamation"></i> لم يُعدّ بعد
                        </span>
                    @endif
                </td>
                <td class="text-center align-middle">
                    <a href="{{ route('shuoun.doctors.assignments', $doctor->id) }}"
                       class="btn btn-sm btn-outline-primary me-1">
                        <i class="bi bi-people"></i> إسناد طلاب
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#del{{ $doctor->id }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>

            {{-- Delete Modal --}}
            <div class="modal fade" id="del{{ $doctor->id }}" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white py-2">
                            <h6 class="modal-title mb-0">تأكيد الحذف</h6>
                            <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center py-3">
                            <i class="bi bi-exclamation-triangle-fill text-danger"
                               style="font-size:2rem;"></i>
                            <p class="mt-2 mb-0">
                                حذف <strong>{{ $doctor->name }}</strong>؟
                            </p>
                            <small class="text-muted">
                                سيتم حذف {{ $doctor->students_count }} تعيين طالب معه
                            </small>
                        </div>
                        <div class="modal-footer py-2">
                            <button class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">إلغاء</button>
                            <form method="POST"
                                  action="{{ route('shuoun.doctors.delete', $doctor->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    تأكيد الحذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection