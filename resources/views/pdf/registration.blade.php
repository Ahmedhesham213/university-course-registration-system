<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
        font-family: 'XB Zar', 'DejaVu Sans', sans-serif;
        font-size: 12px;
        color: #1a1a1a;
        direction: rtl;
        text-align: right;
    }
    .page { padding: 20px; }
    .header {
        text-align: center;
        border-bottom: 3px double #1a1f2e;
        padding-bottom: 12px;
        margin-bottom: 18px;
    }
    .header h1 { font-size:15px; color:#1a1f2e; margin-bottom:4px; }
    .header p  { font-size:10px; color:#666; margin:2px 0; }
    .section-title {
        background: #1a1f2e;
        color: #fff;
        padding: 5px 10px;
        font-size: 12px;
        margin: 14px 0 8px;
    }
    table.info {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }
    table.info td {
        border: 1px solid #ddd;
        padding: 6px 8px;
        width: 25%;
        vertical-align: top;
    }
    .lbl { font-size:10px; color:#888; margin-bottom:2px; }
    .val { font-size:11px; font-weight:bold; color:#1a1a1a; }
    table.subjects {
        width: 100%;
        border-collapse: collapse;
        margin-top: 6px;
    }
    table.subjects th {
        background: #f0f0f0;
        padding: 7px 8px;
        border: 1px solid #ddd;
        font-size: 11px;
        text-align: right;
    }
    table.subjects td {
        padding: 6px 8px;
        border: 1px solid #ddd;
        font-size: 11px;
        text-align: right;
    }
    table.subjects tr:nth-child(even) { background: #fafafa; }
    .total-row { font-weight:bold; background:#f0f0f0 !important; }
    table.signatures {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
    }
    table.signatures td {
        border: 1px solid #ddd;
        width: 33.33%;
        text-align: center;
        padding: 10px 8px;
        vertical-align: bottom;
    }
    .sig-img   { max-height:55px; max-width:140px; }
    .sig-space { height:55px; }
    .sig-label {
        font-size:10px;
        color:#555;
        margin-top:8px;
        border-top:1px solid #eee;
        padding-top:5px;
        line-height:1.6;
    }
    .approved-stamp { color:#198754; font-size:22px; font-weight:bold; }
    .qr-section { text-align:center; margin-top:18px; }
    .qr-section img { width:80px; height:80px; }
    .qr-note    { font-size:9px; color:#888; margin-top:3px; }
    .unique-id  { font-size:7px; color:#bbb; letter-spacing:1px; margin-top:4px; word-break:break-all; }
    .footer { margin-top:20px; border-top:1px solid #ddd; padding-top:8px; }
    .footer table { width:100%; border-collapse:collapse; }
    .footer td { border:none; font-size:9px; color:#aaa; padding:0; }
    .watermark {
        position:fixed; top:38%; left:20%;
        opacity:0.04; font-size:80px; font-weight:900;
        color:#000; transform:rotate(-30deg); z-index:-1;
    }
</style>
</head>
<body>
<div class="page">

<div class="watermark">MTIS</div>

{{-- Header --}}
<div class="header">
    <h1>كلية Management Technology and Information Systems</h1>
    <p>نظام تسجيل المواد الإلكتروني — MTIS</p>
    <p>ورقة تسجيل رسمية — العام الدراسي {{ date('Y') }}/{{ date('Y')+1 }}</p>
</div>

{{-- بيانات الطالب --}}
<div class="section-title">بيانات الطالب</div>
<table class="info">
    <tr>
        <td><div class="lbl">الاسم الكامل</div><div class="val">{{ $form->student->name }}</div></td>
        <td><div class="lbl">الرقم الأكاديمي</div><div class="val">{{ $form->student->academic_id }}</div></td>
        <td><div class="lbl">الرقم القومي</div><div class="val">{{ $form->national_id }}</div></td>
        <td><div class="lbl">البريد الأكاديمي</div><div class="val">{{ $form->academic_email }}</div></td>
    </tr>
    <tr>
        <td>
            <div class="lbl">القسم</div>
            <div class="val">
                @php
                $depts = [
                    'information_systems'     => 'نظم المعلومات',
                    'business_administration' => 'إدارة الأعمال',
                    'accounting'              => 'محاسبة',
                    'marketing'               => 'تسويق',
                ];
                @endphp
                {{ $depts[$form->department] ?? $form->department }}
            </div>
        </td>
        <td><div class="lbl">المستوى الدراسي</div><div class="val">المستوى {{ $form->level }}</div></td>
        <td><div class="lbl">رقم إيصال الدفع</div><div class="val">{{ $form->receipt_number }}</div></td>
        <td><div class="lbl">المرشد الأكاديمي</div><div class="val">{{ $form->academic_advisor_name }}</div></td>
    </tr>
</table>

{{-- المواد --}}
<div class="section-title">المواد الدراسية المسجلة</div>
<table class="subjects">
    <thead>
        <tr>
            <th style="width:8%; text-align:center;">#</th>
            <th style="width:50%">اسم المادة</th>
            <th style="width:22%; text-align:center;">كود المادة</th>
            <th style="width:20%; text-align:center;">الساعات</th>
        </tr>
    </thead>
    <tbody>
        @php $i=1; $totalHours=0; @endphp
        @foreach($form->subjects as $subject)
            @if(isset($subject['selected']) && $subject['selected'])
            <tr>
                <td style="text-align:center;">{{ $i++ }}</td>
                <td>{{ $subject['name'] }}</td>
                <td style="text-align:center;">{{ $subject['code'] }}</td>
                <td style="text-align:center;">{{ $subject['hours'] }}</td>
            </tr>
            @php $totalHours += (int)$subject['hours']; @endphp
            @endif
        @endforeach
        <tr class="total-row">
            <td colspan="3" style="text-align:left; padding-right:10px;">إجمالي الساعات المسجلة</td>
            <td style="text-align:center;">{{ $totalHours }} ساعة</td>
        </tr>
    </tbody>
</table>

{{-- التوقيعات --}}
<div class="section-title">التوقيعات والاعتماد الرسمي</div>
<table class="signatures">
    <tr>
        {{-- توقيع الطالب --}}
        <td>
            @php
                try { $studentSig = decrypt($form->student_signature); }
                catch(\Exception $e) { $studentSig = null; }
            @endphp
            @if($studentSig)
                <img src="{{ $studentSig }}" class="sig-img">
            @else
                <div class="sig-space"></div>
            @endif
            <div class="sig-label">
                توقيع الطالب<br>
                <strong>{{ $form->student->name }}</strong><br>
                {{ $form->created_at->format('Y/m/d') }}
            </div>
        </td>

        {{-- توقيع الدكتور + الختم --}}
        <td>
            @php
                $doctorSig = null;
                if($form->doctor_signature) {
                    try { $doctorSig = decrypt($form->doctor_signature); }
                    catch(\Exception $e) { $doctorSig = null; }
                }
                $stampBase64 = null;
                if($form->doctor_stamp_path) {
                    $fullStamp = storage_path('app/' . $form->doctor_stamp_path);
                    if(file_exists($fullStamp)) {
                        $ext = strtolower(pathinfo($fullStamp, PATHINFO_EXTENSION));
                        $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
                        $stampBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullStamp));
                    }
                }
            @endphp
            @if($doctorSig)
                <img src="{{ $doctorSig }}" class="sig-img">
            @else
                <div class="sig-space"></div>
            @endif
            @if($stampBase64)
                <br><img src="{{ $stampBase64 }}" style="max-height:40px; max-width:100px; margin-top:4px; opacity:0.85;">
            @endif
            <div class="sig-label">
                توقيع المرشد الأكاديمي<br>
                <strong>{{ $form->academic_advisor_name }}</strong><br>
                {{ $form->doctor_signed_at?->format('Y/m/d') }}
            </div>
        </td>

        {{-- اعتماد الشؤون --}}
        <td>
            <div class="sig-space" style="display:flex; align-items:center; justify-content:center;">
                <div class="approved-stamp">✓ معتمد</div>
            </div>
            <div class="sig-label">
                اعتماد الشؤون الطلابية<br>
                <strong>إدارة الشؤون — MTIS</strong><br>
                {{ $form->approved_at?->format('Y/m/d') }}
            </div>
        </td>
    </tr>
</table>

{{-- QR Code --}}
@if($form->qr_code_path)
@php
    $qrFullPath = storage_path('app/public/' . $form->qr_code_path);
    $qrBase64   = null;
    if(file_exists($qrFullPath)) {
        $qrBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($qrFullPath));
    }
@endphp
@if($qrBase64)
<div class="qr-section">
    <img src="{{ $qrBase64 }}">
    <div class="qr-note">امسح الكود للتحقق من أصالة الوثيقة</div>
    <div class="unique-id">{{ $form->unique_hash }}</div>
</div>
@endif
@endif

{{-- Footer --}}
<div class="footer">
    <table>
        <tr>
            <td style="text-align:right;">تاريخ الإصدار: {{ $form->approved_at?->format('Y/m/d H:i') }}</td>
            <td style="text-align:center;">وثيقة رسمية معتمدة — MTIS System</td>
            <td style="text-align:left; font-size:7px;">{{ substr($form->unique_hash, 0, 32) }}</td>
        </tr>
    </table>
</div>

</div>
</body>
</html>