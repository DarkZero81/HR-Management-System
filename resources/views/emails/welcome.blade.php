<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مرحباً بك</title>
</head>
<body style="font-family: 'Tajawal', Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; padding: 32px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);">
        <div style="text-align: center; margin-bottom: 24px;">
            <h1 style="color: #1f2937; font-size: 24px; margin: 0;">HR Engine</h1>
            <p style="color: #6b7280; font-size: 14px; margin: 4px 0 0;">نظام الموارد البشرية</p>
        </div>

        <div style="text-align: center; margin-bottom: 24px;">
            <h2 style="color: #1f2937; font-size: 20px; margin: 0;">مرحباً بك، {{ $userName }}! 👋</h2>
        </div>

        <div style="background-color: #ecfdf5; border-radius: 8px; padding: 24px; text-align: center; margin-bottom: 24px;">
            <p style="color: #065f46; font-size: 16px; margin: 0;">
                تم تفعيل حسابك بنجاح في نظام HR Engine.
            </p>
            <p style="color: #065f46; font-size: 14px; margin: 12px 0 0;">
                يمكنك الآن تسجيل الدخول والاستفادة من جميع الخدمات الذاتية.
            </p>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/login') }}" style="display: inline-block; background-color: #3b82f6; color: #ffffff; text-decoration: none; padding: 12px 32px; border-radius: 8px; font-weight: bold; font-size: 14px;">
                تسجيل الدخول
            </a>
        </div>
    </div>
</body>
</html>