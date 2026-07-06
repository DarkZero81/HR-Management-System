<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>التقرير المالي الرسمي للشركة</title>
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
            font-size: 22px;
            font-weight: 900;
        }
        .header h3 {
            margin: 0 0 4px;
            font-size: 16px;
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
        .summary-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }
        .summary-item {
            flex: 1;
            min-width: 120px;
            padding: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
        }
        .summary-item .value {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
            margin: 4px 0;
        }
        .summary-item .label {
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>شركة المنقذ لإدارة الموارد البشرية</h2>
        <h3>التقرير المالي الرسمي</h3>
        <p>تاريخ الإصدار: {{ $date }}</p>
    </div>

    <div class="section">
        <h4>ملخص البيانات المالية:</h4>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="value">{{ number_format($financialData['totalBaseSalary'], 2) }} ل.س</div>
                <div class="label">إجمالي الرواتب الأساسية</div>
            </div>
            <div class="summary-item">
                <div class="value">{{ number_format($financialData['totalNetSalary'], 2) }} ل.س</div>
                <div class="label">إجمالي الرواتب الصافية</div>
            </div>
            <div class="summary-item">
                <div class="value">{{ number_format($financialData['totalAllowances'], 2) }} ل.س</div>
                <div class="label">إجمالي البدلات</div>
            </div>
            <div class="summary-item">
                <div class="value">{{ number_format($financialData['totalDeductions'], 2) }} ل.س</div>
                <div class="label">إجمالي الخصومات</div>
            </div>
        </div>
    </div>

    <div class="section">
        <h4>توزيع الموظفين حسب الأقسام:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم القسم</th>
                    <th>عدد الموظفين</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                    <tr>
                        <td>{{ $dept->name }}</td>
                        <td>{{ $dept->employees_count }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align: center;">لا توجد أقسام مضافة</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h4>تفاصيل الرواتب الشهرية:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>الموظف</th>
                    <th>الراتب الأساسي</th>
                    <th>البدلات</th>
                    <th>الخصومات</th>
                    <th>صافي الراتب</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaryData as $payroll)
                    <tr>
                        <td>{{ $payroll->employee->full_name ?? '—' }}</td>
                        <td>{{ number_format($payroll->employee->base_salary ?? 0, 2) }} ل.س</td>
                        <td class="text-emerald-600">+{{ number_format($payroll->allowances, 2) }} ل.س</td>
                        <td class="text-red-600">-{{ number_format($payroll->deductions, 2) }} ل.س</td>
                        <td class="font-bold">{{ number_format($payroll->net_salary, 2) }} ل.س</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">لا توجد كشوف رواتب لهذا الشهر</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="total-box">
        صافي الكلفة الشهرية: {{ number_format($financialData['totalNetSalary'], 2) }} ل.س
    </div>

    <div class="footer">
        <span>التقرير المالي الرسمي - نظام الموارد البشرية</span>
    </div>

</body>
</html>