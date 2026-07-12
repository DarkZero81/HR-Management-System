<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>قسيمة الراتب الرسمية</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            padding: 24px;
            color: #1f2937;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 28px;
        }
        .header h2 {
            margin: 0 0 6px;
            font-size: 20px;
            font-weight: 900;
        }
        .header h3 {
            margin: 0 0 4px;
            font-size: 14px;
            font-weight: 700;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #4b5563;
        }
        .section {
            margin-bottom: 22px;
        }
        .section h4 {
            margin: 0 0 10px;
            font-size: 14px;
            font-weight: 800;
            color: #111827;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .table th, .table td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: right;
        }
        .table th {
            background-color: #f3f4f6;
            width: 35%;
            font-weight: 700;
        }
        .table td {
            font-weight: 600;
            color: #111827;
        }
        .total-box {
            margin-top: 28px;
            padding: 16px;
            background-color: #ecfdf5;
            border: 1px solid #10b981;
            font-weight: 900;
            font-size: 16px;
            color: #065f46;
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ $company_name ?? 'المنقذ' }}</h2>
        <h3>قسيمة الراتب الشهري</h3>
        <p>تاريخ الإصدار: {{ $date }}</p>
    </div>

    <div class="section">
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
    </div>

    <div class="section">
        <h4>تفاصيل المستحقات المالية - شهر {{ $payroll->salary_month }}:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>البند</th>
                    <th>المبلغ (ل.س)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>الراتب الأساسي</td>
                    <td>{{ number_format($employee->base_salary, 2) }}</td>
                </tr>
                <tr>
                    <td>البدلات والمكافآت</td>
                    <td>+{{ number_format($payroll->allowances, 2) }}</td>
                </tr>
                <tr>
                    <td>الخصومات</td>
                    <td>-{{ number_format($payroll->deductions, 2) }}</td>
                </tr>
                <tr>
                    <td>رصيد الإجازات المتاح</td>
                    <td>{{ $employee->vacation_balance }} يوم</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="total-box">
        إجمالي صافي الراتب المستحق: {{ number_format($payroll->net_salary, 2) }} ل.س
    </div>

    <div class="footer">
        <span>خصم تلقائي لتأخير الحضور وجزاءات الموارد البشرية المعتمدة</span>
        <span>رقم الكشف: {{ str_pad((string) $payroll->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

</body>
</html>
