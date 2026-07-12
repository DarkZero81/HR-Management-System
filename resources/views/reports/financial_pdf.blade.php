<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>التقرير المالي الرسمي للشركة</title>
    <style>
        body {
            font-family: dejavusans, sans-serif;
            direction: rtl;
            text-align: right;
            padding: 24px;
            color: #1f2937;
            font-size: 13px;
            line-height: 1.7;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1e40af;
            padding-bottom: 16px;
            margin-bottom: 32px;
        }
        .header h2 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 900;
            color: #1e40af;
            letter-spacing: 0.5px;
        }
        .header h3 {
            margin: 0 0 6px;
            font-size: 18px;
            font-weight: 700;
            color: #374151;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #6b7280;
        }
        .section {
            margin-bottom: 24px;
        }
        .section h4 {
            margin: 0 0 12px;
            font-size: 15px;
            font-weight: 800;
            color: #111827;
            border-right: 4px solid #3b82f6;
            padding-right: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .table th, .table td {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: right;
        }
        .table th {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
        }
        .table td {
            font-weight: 600;
            color: #111827;
        }
        .total-box {
            margin-top: 28px;
            padding: 18px;
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #10b981;
            font-weight: 900;
            font-size: 16px;
            color: #065f46;
            text-align: right;
            border-radius: 8px;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
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
            padding: 14px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
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
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>{{ \App\Models\SystemSetting::where('setting_key', 'company_name')->value('setting_value') ?? 'المنقذ' }}</h2>
        <h3>التقرير المالي الرسمي - {{ $month }}</h3>
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
        <h4>الإجازات الرسمية:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>من</th>
                    <th>إلى</th>
                    <th>النوع</th>
                </tr>
            </thead>
            <tbody>
                @forelse($holidays as $holiday)
                    <tr>
                        <td>{{ $holiday->holiday_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($holiday->start_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($holiday->end_date)->format('Y-m-d') }}</td>
                        <td>{{ $holiday->is_recurring ? 'سنوية' : 'مرة واحدة' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">لا توجد إجازات رسمية هذا الشهر</td>
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
                        <td style="color: #065f46; font-weight: 700;">+{{ number_format($payroll->allowances, 2) }} ل.س</td>
                        <td style="color: #991b1b; font-weight: 700;">-{{ number_format($payroll->deductions, 2) }} ل.س</td>
                        <td style="font-weight: 900; color: #111827;">{{ number_format($payroll->net_salary, 2) }} ل.س</td>
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
        <span>HR Engine © {{ date('Y') }}</span>
    </div>

</body>
</html>