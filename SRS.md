# HR Engine — مواصفات المشروع

## 1. نظرة عامة

### المشكلة
تعاني الشركات متوسطة الحجم من إدارة ورقية بطيئة للطلبات، حساب يدوي للرواتب، متابعة حضور غير موثوقة، ضياع الوثائق، وغياب الرقابة على بيانات النظام.

### الحل
نظام ويب متكامل (Intranet) لأتمتة الموارد البشرية مبني على Laravel 13، يعتمد على الخدمة الذاتية Self-Service حيث يدير كل موظف بياناته بنفسه.

### الأهداف
- أتمتة دورة الطلبات من التقديم حتى الاعتماد.
- حساب الراتب الصافي تلقائياً من الحضور والبدلات والخصومات.
- ربط الحضور بأجهزة البصمة مع احتساب تأخير/إضافي آلي.
- أرشيف رقمي مركزي لوثائق الموظفين.
- سجل تدقيق شامل لكل عمليات النظام.

### الميزات الأساسية
- مصادقة OTP مع حماية Lockout
- محرك حضور وانصراف + أجهزة بصمة
- محرك رواتب + قسائم PDF عربي
- دورة طلبات (إجازة، إذن، سلفة، ترقية)
- أرشيف وثائق مع إشعارات انتهاء
- سجل تدقيق Audit Log
- تقارير مالية وتشغيلية (PDF, CSV)
- رسائل SMS/WhatsApp جماعية
- وضع داكن/فاتح + تصميم متجاوب

---

## 2. الأدوار والصلاحيات

| الدور | الوصف |
|-------|-------|
| Admin | صلاحيات كاملة على جميع الميزات |
| Manager | إدارة قسمه، اعتماد طلبات فريقه، تعديل حضور فريقه |
| Employee | بوابة الخدمة الذاتية: حضور شخصي، طلبات، وثائق، راتب |

---

## 3. واجهات المستخدم والتدفقات

### الواجهات (8 واجهات رئيسية):
1. **اللوحة الرئيسية Dashboard** — مؤشرات عامة
2. **الحضور Attendance** — إدارة وتعديل سجل الحضور + My Attendance شخصي
3. **الموظفين Employees** — CRUD ملفات الموظفين + PDF
4. **الطلبات Requests** — تقديم واعتماد الطلبات + Modal تفاصيل + CSV
5. **الرواتب Payroll** — توليد أوامر الرواتب + قسائم PDF
6. **الوثائق Documents** — أرشيف وثائق + My Documents شخصي
7. **الأقسام/الورديات/العطل/أجهزة البصمة** — إعدادات النظام
8. **التقارير Reports** — تقارير مالية PDF + CSV

### التدفقات الرئيسية (4 تدفقات):
1. **تدفق الحضور:** تسجيل دخول ← بصمة/يدوي ← احتساب تأخير ← تسجيل خروج ← احتساب إضافي
2. **تدفق الطلبات:** تقديم ← مراجعة مدير ← اعتماد إدارة ← تنفيذ/خصم تلقائي
3. **تدفق الرواتب:** توليد أوامر ← مراجعة ← اعتماد ← صرف ← قسيمة PDF
4. **تدفق الوثائق:** رفع ← تتبع انتهاء ← إشعار ← تجديد/حذف

---

## 4. بنية قاعدة البيانات

### الجداول والعلاقات:
```
[roles_permissions] 1 --- * [users]
[users] 1 --- 1 [employees] (اختياري)
[departments] 1 --- * [employees]
[shifts] 1 --- * [employees]
[employees] 1 --- * [documents]
[employees] 1 --- * [attendance_logs]
[employees] 1 --- * [hr_transactions]
[employees] 1 --- * [payroll_orders]
[users] 1 --- * [hr_transactions] (approved_by)
[users] 1 --- * [audit_logs]
[users] 1 --- * [otp_codes]
[attendance_devices] 1 --- * [attendance_logs]
[holidays] (standalone)
[system_settings] (standalone)
```

---

### 4.1 roles_permissions
```
id PK BIGINT UNSIGNED AI
role_name VARCHAR(50) UNIQUE NOT NULL  -- admin, manager, employee
description TEXT NULL
created_at, updated_at
deleted_at (soft delete)
```

### 4.2 users
```
id PK BIGINT UNSIGNED AI
name VARCHAR(255) NULL
email VARCHAR(191) UNIQUE NOT NULL
password VARCHAR(255) NOT NULL  -- hashed
avatar VARCHAR(255) NULL
role_id FK -> roles_permissions(id) ON DELETE RESTRICT
is_active TINYINT DEFAULT 1  -- 1=نشط, 0=معطل
remember_token VARCHAR(100) NULL
created_at, updated_at
deleted_at (soft delete)
```

### 4.3 departments
```
id PK BIGINT UNSIGNED AI
name VARCHAR(255) UNIQUE NOT NULL  -- اسم القسم
description TEXT NULL
created_at, updated_at
deleted_at (soft delete)
```

### 4.4 shifts
```
id PK BIGINT UNSIGNED AI
shift_name VARCHAR(100) NOT NULL
start_time TIME NOT NULL
end_time TIME NOT NULL
grace_period_minutes INT DEFAULT 15  -- فترة السماح
is_overnight BOOLEAN DEFAULT FALSE  -- وردية ليلية
created_at, updated_at
deleted_at (soft delete)
```

### 4.5 employees
```
id PK BIGINT UNSIGNED AI
user_id FK -> users(id) ON DELETE SET NULL  -- ربط بحساب المستخدم
shift_id FK -> shifts(id) ON DELETE RESTRICT
department_id FK -> departments(id) ON DELETE SET NULL
avatar VARCHAR(255) NULL
first_name VARCHAR(50) NOT NULL
last_name VARCHAR(50) NOT NULL
national_id VARCHAR(50) UNIQUE NOT NULL  -- الرقم الوطني
phone VARCHAR(20) NULL
base_salary DECIMAL(15,2) NOT NULL  -- الراتب الأساسي
bank_account_iban VARCHAR(50) NULL
join_date DATE NOT NULL  -- تاريخ التعيين
date_of_birth DATE NULL
place_of_birth VARCHAR(100) NULL
education_level ENUM(high_school, diploma, bachelor, master, phd, other) NULL
marital_status ENUM(single, married, divorced, widowed) NULL
nationality VARCHAR(50) NULL
address TEXT NULL
emergency_contact_name VARCHAR(100) NULL
emergency_contact_phone VARCHAR(20) NULL
job_title VARCHAR(100) NULL
resign_date DATE NULL  -- تاريخ الاستقالة
contract_end_date DATE NULL  -- انتهاء العقد
last_promotion_date DATE NULL
insurance_number VARCHAR(50) NULL
vacation_balance INT DEFAULT 21  -- رصيد الإجازات
performance_score DECIMAL(3,2) DEFAULT 0.00  -- تقييم الأداء
created_at, updated_at
deleted_at (soft delete)
```

### 4.6 holidays
```
id PK BIGINT UNSIGNED AI
holiday_name VARCHAR(150) NOT NULL
start_date DATE NOT NULL
end_date DATE NOT NULL
is_recurring TINYINT DEFAULT 0  -- تتكرر سنوياً
created_at, updated_at
deleted_at (soft delete)
```

### 4.7 attendance_devices
```
id PK BIGINT UNSIGNED AI
device_name VARCHAR(100) NOT NULL
ip_address VARCHAR(45) NOT NULL  -- IPv4/IPv6
location VARCHAR(100) NULL
last_seen_at TIMESTAMP NULL
last_sync_at TIMESTAMP NULL
status ENUM(online, offline) DEFAULT 'offline'
last_sync TIMESTAMP NULL
created_at, updated_at
deleted_at (soft delete)
```

### 4.8 documents
```
id PK BIGINT UNSIGNED AI
employee_id FK -> employees(id) ON DELETE CASCADE
document_type ENUM(identity, passport, contract, health_certificate) NOT NULL
document_number VARCHAR(100) NOT NULL
expiry_date DATE NOT NULL  -- تاريخ انتهاء الوثيقة
file_path VARCHAR(255) NOT NULL
created_at, updated_at
deleted_at (soft delete)
```

### 4.9 attendance_logs
```
id PK BIGINT UNSIGNED AI
employee_id FK -> employees(id) ON DELETE CASCADE
device_id FK -> attendance_devices(id) ON DELETE SET NULL
log_date DATE NOT NULL  -- تاريخ اليوم
check_in TIMESTAMP NULL  -- وقت الدخول
check_out TIMESTAMP NULL  -- وقت الخروج
late_minutes INT DEFAULT 0  -- دقائق التأخير
overtime_minutes INT DEFAULT 0  -- دقائق إضافية
status ENUM(present, absent, late, holiday) DEFAULT 'absent'
created_at, updated_at
deleted_at (soft delete)
INDEX: unique_employee_log_date(employee_id, log_date)
```

### 4.10 hr_transactions
```
id PK BIGINT UNSIGNED AI
employee_id FK -> employees(id) ON DELETE CASCADE
transaction_type ENUM(leave, permission, promotion, penalty, transfer) NOT NULL
start_date_time TIMESTAMP NULL
end_date_time TIMESTAMP NULL
description TEXT NULL  -- تفاصيل الطلب
financial_impact DECIMAL(10,2) DEFAULT 0.00  -- الأثر المالي
status ENUM(pending, approved, rejected) DEFAULT 'pending'
approved_by FK -> users(id) ON DELETE SET NULL  -- من وافق
created_at, updated_at
deleted_at (soft delete)
```

### 4.11 payroll_orders
```
id PK BIGINT UNSIGNED AI
employee_id FK -> employees(id) ON DELETE CASCADE
salary_month VARCHAR(7) NOT NULL  -- YYYY-MM
allowances DECIMAL(15,2) DEFAULT 0.00  -- البدلات
deductions DECIMAL(15,2) DEFAULT 0.00  -- الخصومات
net_salary DECIMAL(15,2) NOT NULL  -- الراتب الصافي
payment_status ENUM(draft, approved, paid) DEFAULT 'draft'
paid_at TIMESTAMP NULL  -- تاريخ الصرف
created_at, updated_at
deleted_at (soft delete)
INDEX: unique_employee_salary_month(employee_id, salary_month)
```

### 4.12 audit_logs
```
id PK BIGINT UNSIGNED AI
user_id FK -> users(id) ON DELETE SET NULL  -- من قام بالعملية
action_type ENUM(create, update, delete) NOT NULL
table_name VARCHAR(50) NOT NULL  -- الجدول المتأثر
record_id BIGINT UNSIGNED NOT NULL  -- رقم السجل
old_values TEXT NULL  -- القيم القديمة JSON
new_values TEXT NULL  -- القيم الجديدة JSON
performed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
created_at, updated_at
deleted_at (soft delete)
```

### 4.13 otp_codes
```
id PK BIGINT UNSIGNED AI
email VARCHAR(255) NULL
user_id FK -> users(id) ON DELETE CASCADE
code VARCHAR(255) NOT NULL  -- مشفر
type VARCHAR(20) DEFAULT 'login'
expires_at TIMESTAMP NOT NULL  -- وقت الانتهاء
used_at TIMESTAMP NULL  -- وقت الاستخدام
failed_attempts INT DEFAULT 0  -- المحاولات الفاشلة
locked_until TIMESTAMP NULL  -- وقت القفل
created_at, updated_at
INDEX: idx_email_code(email, code)
INDEX: idx_expires_at(expires_at)
```

### 4.14 system_settings
```
id PK BIGINT UNSIGNED AI
setting_key VARCHAR(100) UNIQUE NOT NULL
setting_value TEXT NOT NULL
created_at, updated_at
deleted_at (soft delete)
```

---

## 5. المكونات البرمجية

### Models (14 Model):
```
RolePermission, User, Department, Shift, Employee,
Holiday, AttendanceDevice, Document, AttendanceLog,
HrTransaction, PayrollOrder, AuditLog, OtpCode, SystemSetting
```

### Controllers (13 Web Controller):
```
AttendanceWebController, DashboardController, DepartmentWebController,
DeviceWebController, DocumentWebController, EmployeeWebController,
HolidayWebController, OtpController, PayrollWebController,
ProfileController, RequestWebController, ShiftWebController, SmsMessageController
```

### API Controllers (11 Api Controller):
```
AuthController, EmployeeController, DepartmentApiController,
ShiftController, AttendanceLogController, AttendanceDeviceController,
HolidayController, DocumentController, HrTransactionController,
PayrollOrderController, AuditLogController, SystemSettingController,
RolePermissionController
```

### Middleware (2 custom):
```
EnsureUserIsActive -- يمنع دخول الحسابات المعطلة
RoleMiddleware -- يتحقق من صلاحية الدور
```

### Observers (3):
```
DepartmentObserver, ShiftObserver, HolidayObserver
```

### Notifications (4):
```
WelcomeNotification, OtpNotification, DocumentExpiryNotification, CustomMessageNotification
```

### Console Commands (2):
```
SendDocumentExpiryNotifications -- إشعارات انتهاء الوثائق
MarkAbsentDaily -- تسجيل الغياب اليومي تلقائياً
```

---

## 6. التقنيات المستخدمة

| الطبقة | التقنية | الإصدار |
|--------|---------|--------|
| Backend | Laravel | 13.x |
| لغة البرمجة | PHP | 8.5+ |
| Frontend | Blade + Tailwind CSS + Alpine.js | - |
| Build Tool | Vite | - |
| PDF Engine | mPDF | - |
| Database | MySQL / MariaDB | 8.x+ |
| أيقونات | Lucide Icons | - |
| المصادقة | Laravel Breeze + OTP | - |
| API | Laravel Sanctum | - |
| Notifications | Laravel Notifications + SMS/WhatsApp | - |
| Cache | Redis / File | - |

---

## 7. خارطة الطريق

### مكتمل:
- مصادقة OTP + حماية Lockout
- إدارة الحضور والانصراف + أجهزة بصمة
- محرك رواتب + PDF
- دورة طلبات متكاملة
- أرشيف وثائق
- Audit Log
- تصدير CSV
- رسائل SMS/WhatsApp
- إعدادات النظام
- API RESTful موحّد
- My Area (بوابة الموظف)

### مخطط مستقبلاً:
- Push Notifications
- Queue للعمليات الثقيلة
- Feature Tests شاملة
- واجهة موبايل مستقلة

---

**المشروع:** HR Engine  
**الإصدار:** 1.0  
**المطوّر:** عبد الرحمن المصطفى  
**الرخصة:** MIT  
**المستودع:** https://github.com/DarkZero81/HR-Management-System
