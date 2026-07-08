<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق</title>
</head>
<body style="font-family: 'Tajawal', Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; padding: 32px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <div style="text-align: center; margin-bottom: 24px;">
            <h1 style="color: #1f2937; font-size: 24px; margin: 0;">HR Engine</h1>
            <p style="color: #6b7280; font-size: 14px; margin: 4px 0 0;">نظام الموارد البشرية</p>
        </div>

        <div style="background-color: #eff6ff; border-radius: 8px; padding: 24px; text-align: center; margin-bottom: 24px;">
            <p style="color: #1e40af; font-size: 16px; margin: 0 0 16px;">{{ $body }}</p>
            <div style="background-color: #ffffff; border-radius: 8px; padding: 16px; display: inline-block; min-width: 200px;">
                <span style="font-size: 32px; font-weight: bold; color: #1f2937; letter-spacing: 8px;">{{ $code }}</span>
            </div>
            <p style="color: #6b7280; font-size: 14px; margin: 16px 0 0;">هذا الرمز صالح لمدة 5 دقائق</p>
        </div>

        <div style="text-align: center; color: #9ca3af; font-size: 12px;">
            <p>إذا لم تطلب هذا الرمز، يرجى تجاهل هذا البريد الإلكتروني.</p>
        </div>
    </div>
</body>
</html>