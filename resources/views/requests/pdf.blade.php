<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>طلب #{{ $transaction->id }}</title>
    <style>
        body { font-family: dejavusans, sans-serif; direction: rtl; text-align: right; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 22px; margin-bottom: 5px; }
        .header p { color: #666; font-size: 14px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 10px; border-bottom: 1px solid #eee; vertical-align: top; }
        .info-table td:first-child { width: 35%; font-weight: bold; color: #555; }
        .status { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .section { margin-top: 25px; }
        .section h2 { font-size: 16px; margin-bottom: 10px; color: #333; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px; }
        .footer { margin-top: 40px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>تفاصيل الطلب</h1>
        <p>طلب رقم #{{ $transaction->id }} | تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td>نوع الطلب</td>
            <td>{{ match($transaction->transaction_type) { 'leave' => 'إجازة', 'permission' => 'إذن', 'promotion' => 'ترقية', 'penalty' => 'عقوبة', 'transfer' => 'نقل', default => $transaction->transaction_type } }}</td>
        </tr>
        <tr>
            <td>الحالة</td>
            <td>
                <span class="status {{ match($transaction->status) { 'pending' => 'status-pending', 'approved' => 'status-approved', 'rejected' => 'status-rejected', default => 'status-pending' } }}">
                    {{ match($transaction->status) { 'pending' => 'معلقة', 'approved' => 'موافق عليها', 'rejected' => 'مرفوضة', default => $transaction->status } }}
                </span>
            </td>
        </tr>
        <tr>
            <td>تاريخ البداية</td>
            <td>{{ \Carbon\Carbon::parse($transaction->start_date_time)->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td>تاريخ النهاية</td>
            <td>{{ \Carbon\Carbon::parse($transaction->end_date_time)->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td>مدة الطلب</td>
            <td>{{ (int) (\Carbon\Carbon::parse($transaction->start_date_time)->diffInDays(\Carbon\Carbon::parse($transaction->end_date_time)) + 1) }} يوم</td>
        </tr>
        <tr>
            <td>التأثير المالي</td>
            <td>{{ number_format($transaction->financial_impact, 2) }} ل.س</td>
        </tr>
        <tr>
            <td>ملاحظات</td>
            <td>{{ $transaction->description ?? 'لا توجد ملاحظات.' }}</td>
        </tr>
    </table>

    <div class="section">
        <h2>مقدم الطلب</h2>
        <table class="info-table">
            <tr><td>الاسم الكامل</td><td>{{ $transaction->employee->full_name ?? '—' }}</td></tr>
            <tr><td>البريد الإلكتروني</td><td>{{ $transaction->employee->email ?? '—' }}</td></tr>
            <tr><td>القسم</td><td>{{ $transaction->employee->department?->name ?? 'غير معين' }}</td></tr>
            <tr><td>الوردية</td><td>{{ $transaction->employee->shift?->shift_name ?? 'بدون وردية' }}</td></tr>
            <tr><td>تاريخ التعيين</td><td>{{ $transaction->employee->join_date?->format('Y-m-d') ?? '-' }}</td></tr>
        </table>
    </div>

    @if($transaction->approver)
    <div class="section">
        <h2>معلومات المراجعة</h2>
        <table class="info-table">
            <tr><td>اسم المراجع</td><td>{{ $transaction->approver->name ?? '—' }}</td></tr>
            <tr><td>بريد المراجع</td><td>{{ $transaction->approver->email ?? '—' }}</td></tr>
        </table>
    </div>
    @endif

    <div class="section">
        <h2>معلومات النظام</h2>
        <table class="info-table">
            <tr><td>رقم الطلب</td><td>#{{ $transaction->id }}</td></tr>
            <tr><td>تاريخ الإنشاء</td><td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td></tr>
            <tr><td>آخر تحديث</td><td>{{ $transaction->updated_at->format('Y-m-d H:i') }}</td></tr>
        </table>
    </div>

    <div class="footer">
        تم إنشاء هذا التقرير تلقائياً بواسطة نظام الموارد البشرية | {{ now()->format('Y-m-d H:i') }}
    </div>
</body>
</html>
