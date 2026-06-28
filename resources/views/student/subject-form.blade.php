@extends('layouts.app')
@section('title', 'اختيار المواد والتوقيع')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card p-4">
    <h5 class="fw-bold mb-1"><i class="bi bi-list-check"></i> ورقة تسجيل المواد</h5>
    <p class="text-muted small mb-4">الخطوة 2 من 2 — اختر المواد ووقّع</p>

    <form method="POST" action="{{ route('student.step2.submit', $form->id) }}">
        @csrf

        @if($template && count($template->subjects) > 0)
        <div class="mb-4">
            <h6 class="fw-bold mb-3">المواد المتاحة — {{ $form->department }} / المستوى {{ $form->level }}</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr><th width="50">اختر</th><th>اسم المادة</th><th>الكود</th><th>الساعات</th></tr>
                    </thead>
                    <tbody>
                        @foreach($template->subjects as $i => $subject)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="subjects[{{ $i }}][selected]" value="1"
                                    class="form-check-input subject-check">
                                <input type="hidden" name="subjects[{{ $i }}][name]" value="{{ $subject['name'] }}">
                                <input type="hidden" name="subjects[{{ $i }}][code]" value="{{ $subject['code'] }}">
                                <input type="hidden" name="subjects[{{ $i }}][hours]" value="{{ $subject['hours'] }}">
                            </td>
                            <td>{{ $subject['name'] }}</td>
                            <td><code>{{ $subject['code'] }}</code></td>
                            <td>{{ $subject['hours'] }} س</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info py-2">
                <strong>إجمالي الساعات المختارة: <span id="total-hours">0</span></strong>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            لا يوجد فورم مواد لقسمك ومستواك حتى الآن. تواصل مع الشؤون.
        </div>
        @endif

        <!-- توقيع الطالب -->
        <div class="mb-4">
            <h6 class="fw-bold mb-2">توقيع الطالب</h6>
            <p class="text-muted small">ارسم توقيعك بالماوس أو بإصبعك على الشاشة</p>
            <div style="border:2px solid #dee2e6; border-radius:8px; background:#fff; cursor:crosshair;">
                <canvas id="signature-pad" width="700" height="150" style="width:100%; touch-action:none;"></canvas>
            </div>
            <div class="mt-2 d-flex gap-2">
                <button type="button" onclick="clearSignature()" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eraser"></i> مسح التوقيع
                </button>
            </div>
            <input type="hidden" name="student_signature" id="student_signature">
        </div>

        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">رجوع</a>
            <button type="submit" onclick="return saveSignature()" class="btn btn-success px-4 fw-bold">
                <i class="bi bi-send"></i> إرسال للمرشد الأكاديمي
            </button>
        </div>
    </form>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
// Signature Pad
const canvas = document.getElementById('signature-pad');
const ctx = canvas.getContext('2d');
let drawing = false, lastX = 0, lastY = 0;

function getPos(e) {
    const r = canvas.getBoundingClientRect();
    const scaleX = canvas.width / r.width;
    const scaleY = canvas.height / r.height;
    if (e.touches) {
        return { x: (e.touches[0].clientX - r.left) * scaleX, y: (e.touches[0].clientY - r.top) * scaleY };
    }
    return { x: (e.clientX - r.left) * scaleX, y: (e.clientY - r.top) * scaleY };
}

canvas.addEventListener('mousedown',  e => { drawing = true; const p = getPos(e); [lastX, lastY] = [p.x, p.y]; });
canvas.addEventListener('mousemove',  e => { if (!drawing) return; const p = getPos(e); draw(p.x, p.y); });
canvas.addEventListener('mouseup',    () => drawing = false);
canvas.addEventListener('touchstart', e => { e.preventDefault(); drawing = true; const p = getPos(e); [lastX, lastY] = [p.x, p.y]; });
canvas.addEventListener('touchmove',  e => { e.preventDefault(); if (!drawing) return; const p = getPos(e); draw(p.x, p.y); });
canvas.addEventListener('touchend',   () => drawing = false);

function draw(x, y) {
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(x, y);
    ctx.strokeStyle = '#1a1f2e';
    ctx.lineWidth = 2.5;
    ctx.lineCap = 'round';
    ctx.stroke();
    [lastX, lastY] = [x, y];
}

function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function isEmpty() {
    return !ctx.getImageData(0, 0, canvas.width, canvas.height).data.some(v => v !== 0);
}

function saveSignature() {
    if (isEmpty()) { alert('الرجاء رسم توقيعك أولاً'); return false; }
    const checked = document.querySelectorAll('.subject-check:checked');
    if (checked.length === 0) { alert('الرجاء اختيار مادة واحدة على الأقل'); return false; }
    document.getElementById('student_signature').value = canvas.toDataURL('image/png');
    return true;
}

// حساب الساعات
document.querySelectorAll('.subject-check').forEach(cb => {
    cb.addEventListener('change', updateHours);
});

function updateHours() {
    let total = 0;
    document.querySelectorAll('.subject-check:checked').forEach(cb => {
        const row = cb.closest('tr');
        const hours = parseInt(row.cells[3].textContent);
        total += hours;
    });
    document.getElementById('total-hours').textContent = total;
}
</script>
@endpush