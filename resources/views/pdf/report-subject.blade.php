<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'XB Zar','DejaVu Sans',sans-serif; font-size:12px; color:#1a1a1a; direction:rtl; }
    .page { padding:20px; }
    .header { text-align:center; border-bottom:3px double #1a1f2e; padding-bottom:12px; margin-bottom:18px; }
    .header h1 { font-size:16px; color:#1a1f2e; margin-bottom:4px; }
    .header p  { font-size:10px; color:#666; margin:2px 0; }
    .subject-box { background:#1a1f2e; color:#fff; padding:12px 16px; border-radius:6px; margin-bottom:18px; }
    .subject-box h2 { font-size:14px; margin-bottom:6px; }
    .subject-box .meta { font-size:11px; opacity:.8; }
    .badge { display:inline-block; padding:2px 10px; border-radius:20px; font-size:10px; font-weight:bold; margin-right:6px; }
    .badge-blue  { background:#4f8ef7; color:#fff; }
    .badge-gray  { background:#adb5bd; color:#fff; }
    table { width:100%; border-collapse:collapse; margin-top:8px; }
    thead th { background:#f0f0f0; padding:7px 8px; border:1px solid #ddd; font-size:11px; text-align:right; }
    tbody td { padding:6px 8px; border:1px solid #ddd; font-size:11px; }
    tbody tr:nth-child(even) { background:#fafafa; }
    tfoot td { background:#f0f0f0; padding:6px 8px; border:1px solid #ddd; font-size:11px; font-weight:bold; }
    .footer { margin-top:20px; border-top:1px solid #ddd; padding-top:8px; font-size:9px; color:#aaa; text-align:center; }
    .watermark { position:fixed; top:40%; left:15%; opacity:.04; font-size:70px; font-weight:900; color:#000; transform:rotate(-30deg); }
    .stat-row { display:table; width:100%; margin-bottom:16px; }
    .stat-cell { display:table-cell; text-align:center; padding:10px; border:1px solid #ddd; border-radius:4px; }
    .stat-num { font-size:22px; font-weight:bold; color:#1a1f2e; }
    .stat-lbl { font-size:10px; color:#666; }
</style>
</head>
<body>
<div class="page">
    <div class="watermark">MTIS</div>

    <div class="header">
        <h1>كلية Management Technology and Information Systems</h1>
        <p>تقرير مادة دراسية — نظام MTIS</p>
        <p>تاريخ الإصدار: {{ now()->format('Y/m/d H:i') }}</p>
    </div>

    <div class="subject-box">
        <h2>{{ $subject['name'] }}</h2>
        <div class="meta">
            <span class="badge badge-blue">{{ $subject['code'] }}</span>
            <span class="badge badge-gray">{{ $subject['hours'] }} ساعات معتمدة</span>
        </div>
    </div>

    {{-- إحصائية سريعة --}}
    <table style="margin-bottom:16px;">
        <tr>
            <td style="background:#e8f4fd; border:1px solid #bee5fb; text-align:center; padding:10px; border-radius:4px;">
                <div style="font-size:24px; font-weight:bold; color:#0d6efd;">{{ count($students) }}</div>
                <div style="font-size:10px; color:#666;">إجمالي الطلاب المسجلين</div>
            </td>
        </tr>
    </table>

    {{-- جدول الطلاب --}}
    @if(count($students) > 0)
    <table>
        <thead>
            <tr>
                <th style="width:6%; text-align:center;">#</th>
                <th style="width:22%;">اسم الطالب</th>
                <th style="width:15%;">الرقم الأكاديمي</th>
                <th style="width:25%;">البريد الأكاديمي</th>
                <th style="width:18%;">القسم</th>
                <th style="width:8%; text-align:center;">المستوى</th>
                <th style="width:12%; text-align:center;">تاريخ التسجيل</th>
            </tr>
        </thead>
        <tbody>
            @php
            $depts = [
                'information_systems'     => 'نظم المعلومات',
                'business_administration' => 'إدارة الأعمال',
                'accounting'              => 'محاسبة',
                'marketing'               => 'تسويق',
            ];
            @endphp
            @foreach($students as $i => $student)
            <tr>
                <td style="text-align:center;">{{ $i + 1 }}</td>
                <td>{{ $student['name'] }}</td>
                <td style="text-align:center;">{{ $student['academic_id'] }}</td>
                <td style="font-size:10px;">{{ $student['email'] }}</td>
                <td>{{ $depts[$student['department']] ?? $student['department'] }}</td>
                <td style="text-align:center;">{{ $student['level'] }}</td>
                <td style="text-align:center;">{{ $student['approved_at'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" style="text-align:left; padding-right:10px;">
                    إجمالي الطلاب المسجلين في {{ $subject['name'] }}: {{ count($students) }} طالب
                </td>
            </tr>
        </tfoot>
    </table>
    @else
    <div style="text-align:center; padding:30px; color:#888;">لا يوجد طلاب مسجلون في هذه المادة</div>
    @endif

    <div class="footer">
        تقرير رسمي صادر من نظام MTIS — Management Technology and Information Systems
    </div>
</div>
</body>
</html>
