@extends('layouts.app')
@section('title', 'إسناد الطلاب')
@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('shuoun.doctors') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
    <div>
        <h5 class="fw-bold mb-0">
            إسناد طلاب للدكتور: {{ $doctor->name }}
        </h5>
        <p class="text-muted small mb-0">{{ $doctor->email }}</p>
    </div>
</div>

<div class="row g-4">

    {{-- إضافة بريد --}}
    <div class="col-lg-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-1">
                <i class="bi bi-plus-circle text-primary"></i> إضافة بريد طلاب
            </h6>
            <p class="text-muted small mb-3">
                بريد واحد في كل سطر — لو الطالب عند دكتور آخر هيتحوّل تلقائياً
            </p>
            <form method="POST"
                  action="{{ route('shuoun.doctors.assignments.save', $doctor->id) }}">
                @csrf
                <textarea name="emails" class="form-control mb-3" rows="10"
                    placeholder="student1@mtis.edu.eg&#10;student2@mtis.edu.eg&#10;student3@mtis.edu.eg"
                    required></textarea>
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="bi bi-save"></i> حفظ التعيينات
                </button>
            </form>
        </div>
    </div>

    {{-- قائمة الطلاب --}}
    <div class="col-lg-7">
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-people text-success"></i>
                    الطلاب المسندون
                </h6>
                <span class="badge bg-primary rounded-pill">
                    {{ $assignments->count() }} طالب
                </span>
            </div>

            @if($assignments->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox" style="font-size:2.5rem; opacity:.3;"></i>
                <p class="mt-2 small">لم يتم إسناد أي طلاب بعد</p>
            </div>
            @else
            <div style="max-height:450px; overflow-y:auto;">
                <table class="table table-sm table-hover">
                    <thead class="table-light" style="position:sticky; top:0;">
                        <tr>
                            <th width="40">#</th>
                            <th>البريد الأكاديمي</th>
                            <th>الطالب</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $i => $assign)
                        @php
                            $student = \App\Models\User::where('email', $assign->student_email)
                                ->where('role','student')->first();
                        @endphp
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td class="small">{{ $assign->student_email }}</td>
                            <td>
                                @if($student)
                                <span class="badge"
                                    style="background:#d1e7dd; color:#0a3622;">
                                    <i class="bi bi-person-check"></i>
                                    {{ $student->name }}
                                </span>
                                @else
                                <span class="badge bg-light text-muted border">
                                    لم يسجل بعد
                                </span>
                                @endif
                            </td>
                            <td>
                                <form method="POST"
                                    action="{{ route('shuoun.assignments.delete', $assign->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('حذف هذا التعيين؟')"
                                        title="حذف">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection