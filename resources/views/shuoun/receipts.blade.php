@extends('layouts.app')
@section('title', 'أرقام الإيصالات')
@section('content')
<div class="row g-4">
    <div class="col-md-5">
        <div class="card p-4">
            <h6 class="fw-bold mb-3">إضافة أرقام إيصالات جديدة</h6>
            <form method="POST" action="{{ route('shuoun.receipts.add') }}">
                @csrf
                <label class="form-label">أرقام الإيصالات (رقم واحد في كل سطر، 7 أرقام)</label>
                <textarea name="numbers" class="form-control mb-3" rows="8" placeholder="1234567&#10;7654321&#10;1111111" required></textarea>
                <button type="submit" class="btn btn-primary fw-bold w-100">
                    <i class="bi bi-plus-circle"></i> إضافة
                </button>
            </form>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">سجل الإيصالات</h6>
            <table class="table table-sm table-hover">
                <thead class="table-light"><tr><th>رقم الإيصال</th><th>الحالة</th><th>المستخدم من</th></tr></thead>
                <tbody>
                    @foreach($receipts as $r)
                    <tr>
                        <td><code>{{ $r->receipt_number }}</code></td>
                        <td>
                            @if($r->is_used)
                                <span class="badge bg-secondary">مستخدم</span>
                            @else
                                <span class="badge bg-success">متاح</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $r->usedByUser->name ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $receipts->links() }}
        </div>
    </div>
</div>
@endsection