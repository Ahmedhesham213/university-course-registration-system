@extends('layouts.app')
@section('title', 'إعداد التوقيع والختم')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card p-4">
    <h5 class="fw-bold mb-1"><i class="bi bi-pen"></i> إعداد التوقيع والختم</h5>
    <p class="text-muted small mb-4">يُخزّن التوقيع مشفراً ولا يظهر لأحد — يُستخدم فقط عند التوقيع على الورقة</p>

    @if(auth()->user()->signature_data)
    <div class="alert alert-success mb-4">
        <i class="bi bi-check-circle"></i> التوقيع محفوظ بالفعل. يمكنك تحديثه أدناه.
    </div>
    @endif

    <form method="POST" action="{{ route('doctor.setup.save') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="form-label fw-bold">ارسم توقيعك</label>
            <p class="text-muted small">استخدم الماوس أو الإصبع</p>
            <div style="border:2px solid #dee2e6; border-radius:8px; background:#fff;">
                <canvas id="sig-canvas" width="600" height="150" style="width:100%; touch-action:none; cursor:crosshair;"></canvas>
            </div>
            <button type="button" onclick="clearSig()" class="btn btn-sm btn-outline-secondary mt-2">
                <i class="bi bi-eraser"></i> مسح
            </button>
            <input type="hidden" name="signature_data" id="sig-data">
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">ختم القسم / الكلية (اختياري)</label>
            <p class="text-muted small">ارفع صورة الختم على ورقة بيضاء بخلفية شفافة أو بيضاء</p>
            <input type="file" name="stamp_image" class="form-control" accept="image/*">
        </div>

        <button type="submit" onclick="return prepareSig()" class="btn btn-primary fw-bold px-4">
            <i class="bi bi-save"></i> حفظ التوقيع والختم
        </button>
    </form>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
const canvas = document.getElementById('sig-canvas');
const ctx = canvas.getContext('2d');
let drawing = false, lx = 0, ly = 0;

function getP(e) {
    const r = canvas.getBoundingClientRect();
    const sx = canvas.width / r.width, sy = canvas.height / r.height;
    return e.touches
        ? { x:(e.touches[0].clientX-r.left)*sx, y:(e.touches[0].clientY-r.top)*sy }
        : { x:(e.clientX-r.left)*sx, y:(e.clientY-r.top)*sy };
}
canvas.addEventListener('mousedown',  e => { drawing=true; const p=getP(e); [lx,ly]=[p.x,p.y]; });
canvas.addEventListener('mousemove',  e => { if(!drawing) return; const p=getP(e); draw(p.x,p.y); });
canvas.addEventListener('mouseup',    () => drawing=false);
canvas.addEventListener('touchstart', e => { e.preventDefault(); drawing=true; const p=getP(e); [lx,ly]=[p.x,p.y]; });
canvas.addEventListener('touchmove',  e => { e.preventDefault(); if(!drawing) return; const p=getP(e); draw(p.x,p.y); });
canvas.addEventListener('touchend',   () => drawing=false);

function draw(x, y) {
    ctx.beginPath(); ctx.moveTo(lx,ly); ctx.lineTo(x,y);
    ctx.strokeStyle='#1a1f2e'; ctx.lineWidth=2.5; ctx.lineCap='round'; ctx.stroke();
    [lx,ly]=[x,y];
}
function clearSig() { ctx.clearRect(0,0,canvas.width,canvas.height); }
function isEmpty() { return !ctx.getImageData(0,0,canvas.width,canvas.height).data.some(v=>v!==0); }
function prepareSig() {
    if(isEmpty()) { alert('الرجاء رسم التوقيع أولاً'); return false; }
    document.getElementById('sig-data').value = canvas.toDataURL('image/png');
    return true;
}
</script>
@endpush