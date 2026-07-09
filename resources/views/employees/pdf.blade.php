<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVuSans, sans-serif; direction: rtl; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 22px; margin-bottom: 4px; }
        .header p { font-size: 12px; color: #555; }
        .section { margin-bottom: 16px; }
        .section h2 { font-size: 14px; background: #e5e7eb; padding: 6px 10px; margin-bottom: 8px; border-radius: 6px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .item { border: 1px solid #e5e7eb; border-radius: 8px; padding: 8px 10px; }
        .item label { font-size: 11px; color: #6b7280; display: block; margin-bottom: 2px; }
        .item span { font-size: 13px; font-weight: bold; }
        .footer { margin-top: 24px; font-size: 11px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>HR Engine</h1>
        <p>ملف الموظف - {{ $employee->full_name }}</p>
    </div>

    <div class="section">
        <h2>البيانات الأساسية</h2>
        <div class="grid">
            <div class="item"><label>الاسم الأول</label><span>{{ $employee->first_name }}</span></div>
            <div class="item"><label>الاسم الأخير</label><span>{{ $employee->last_name }}</span></div>
            <div class="item"><label>الرقم الوطني</label><span>{{ $employee->national_id }}</span></div>
            <div class="item"><label>رقم الهاتف</label><span>{{ $employee->phone ?? '-' }}</span></div>
            <div class="item"><label>البريد الإلكتروني</label><span>{{ $employee->user?->email ?? '-' }}</span></div>
            <div class="item"><label>القسم</label><span>{{ $employee->department?->name ?? 'غير معين' }}</span></div>
            <div class="item"><label>الوردية</label><span>{{ $employee->shift?->shift_name ?? 'بدون وردية' }}</span></div>
            <div class="item"><label>الراتب الأساسي</label><span>{{ number_format($employee->base_salary, 2) }} ل.س</span></div>
            <div class="item"><label>تاريخ التعيين</label><span>{{ $employee->join_date?->format('Y-m-d') }}</span></div>
            <div class="item"><label>رصيد الإجازات</label><span>{{ $employee->vacation_balance }} يوم</span></div>
            <div class="item"><label>المسمى الوظيفي</label><span>{{ $employee->job_title ?? '-' }}</span></div>
            <div class="item"><label>تاريخ نهاية العقد</label><span>{{ $employee->contract_end_date?->format('Y-m-d') ?? '-' }}</span></div>
        </div>
    </div>

    <div class="section">
        <h2>بيانات شخصية إضافية</h2>
        <div class="grid">
            <div class="item"><label>تاريخ الميلاد</label><span>{{ $employee->date_of_birth?->format('Y-m-d') ?? '-' }}</span></div>
            <div class="item"><label>العمر</label><span>{{ $employee->age ?? '-' }} سنة</span></div>
            <div class="item"><label>مكان الولادة</label><span>{{ $employee->place_of_birth ?? '-' }}</span></div>
            <div class="item"><label>نوع التعليم</label><span>{{ $employee->education_label ?? '-' }}</span></div>
            <div class="item"><label>الحالة الاجتماعية</label><span>{{ $employee->marital_status_label ?? '-' }}</span></div>
            <div class="item"><label>الجنسية</label><span>{{ $employee->nationality ?? '-' }}</span></div>
            <div class="item"><label>العنوان</label><span>{{ $employee->address ?? '-' }}</span></div>
            <div class="item"><label>جهة اتصال الطوارئ</label><span>{{ $employee->emergency_contact_name ?? '-' }}</span></div>
            <div class="item"><label>هاتف الطوارئ</label><span>{{ $employee->emergency_contact_phone ?? '-' }}</span></div>
            <div class="item"><label>رقم التأمين</label><span>{{ $employee->insurance_number ?? '-' }}</span></div>
        </div>
    </div>

    <div class="footer">
        تم إنشاء هذا الملف تلقائياً من نظام HR Engine بتاريخ {{ now()->format('Y-m-d H:i') }}
    </div>
</body>
</html>
