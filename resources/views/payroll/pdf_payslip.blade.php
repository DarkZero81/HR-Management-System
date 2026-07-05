<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>قسيمة الراتب الرسمية</title>
    <style>
        body {
            font-family: 'XZar', 'DejaVu Sans', sans-serif; /* الخطوط الافتراضية الداعمة للعربية في DomPDF */
            direction: rtl;
            text-align: right;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: right;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total-box {
            margin-top: 30px;
            padding: 15px;
            background-color: #e6fffa;
            border: 1px solid #319795;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>شركة المنقذ لإدارة الموارد البشرية</h2>
        <h3>قسيمة الراتب الشهرة للموظف</h3>
        <p>تاريخ الإصدار: {{ $date }}</p>
    </div>

    <h4>بيانات الموظف الأساسية:</h4>
    <table class="table">
        <tr>
            <th>الاسم الكامل</th>
            <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
            <th>الرقم القومي</th>
            <td>{{ $employee->national_id }}</td>
        </tr>
        <tr>
            <th>القسم الإداري</th>
            <td>{{ $employee->department->name ?? 'غير معين' }}</td>
            <th>الوردية (الدوام)</th>
            <td>{{ $employee->shift->shift_name ?? 'غير معينة' }}</td>
        </tr>
    </table>

    <h4>تفاصيل المستحقات المالية:</h4>
    <table class="table">
        <thead>
            <tr>
                <th>البند</th>
                <th>المبلغ (SAR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>الراتب الأساسي</td>
                <td>{{ number_format($employee->base_salary, 2) }}</td>
            </tr>
            <tr>
                <td>رصيد الإجازات المتاح</td>
                <td>{{ $employee->vacation_balance }} يوم</td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        إجمالي صافي الراتب المستحق: {{ number_format($employee->base_salary, 2) }} ريال سعودي
    </div>
<a href="{{ route('payroll.download_pdf', $employee->id) }}" class="btn btn-sm btn-success">
    <i class="fas fa-file-pdf"></i> تحميل قسيمة الراتب (PDF)
</a>

</body>
</html>
