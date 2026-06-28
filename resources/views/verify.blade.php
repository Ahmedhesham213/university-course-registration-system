<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTIS - التحقق من الوثيقة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">
    @if($valid)
    <div class="card border-success p-4 text-center">
        <div style="font-size:4rem;">✅</div>
        <h4 class="text-success fw-bold mt-2">وثيقة رسمية معتمدة</h4>
        <hr>
        <table class="table text-start">
            <tr><th>الطالب</th><td>{{ $student->name }}</td></tr>
            <tr><th>الرقم الأكاديمي</th><td>{{ $student->academic_id }}</td></tr>
            <tr><th>القسم</th><td>{{ $form->department }}</td></tr>
            <tr><th>المستوى</th><td>{{ $form->level }}</td></tr>
            <tr><th>تاريخ الاعتماد</th><td>{{ $form->approved_at->format('Y/m/d') }}</td></tr>
        </table>
        <div class="text-muted small">هذه الوثيقة صادرة رسمياً من نظام MTIS</div>
    </div>
    @else
    <div class="card border-danger p-4 text-center">
        <div style="font-size:4rem;">❌</div>
        <h4 class="text-danger fw-bold mt-2">وثيقة غير صالحة</h4>
        <p class="text-muted">الرمز غير موجود أو الوثيقة لم يتم اعتمادها</p>
    </div>
    @endif
</div>
</div>
</div>
</body>
</html>