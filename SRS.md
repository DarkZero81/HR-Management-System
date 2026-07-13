# وثيقة متطلبات البرمجيات (Software Requirements Specification — SRS)
## نظام HR Engine — نظام إدارة الموارد البشرية الذاتية

> تم استخراج هذه الوثيقة بالكامل من قراءة ملفات المشروع الفعلية: `composer.json`, `package.json`, `routes/*.php`, `app/Models/*`, `app/Http/Controllers/**`, `database/migrations/*`, `database/seeders/*`, `resources/views/*`.

---

## 1. اسم المشروع (Project Name)

**HR Engine** — نظام إدارة الموارد البشرية الذاتية (Employee Self-Service HR Management System).

- **المجلد البرمجي:** `employer_mange`
- **نوع التطبيق:** Monolith Laravel (بدون فصل واجهة SPA)
- **الإطار:** Laravel 13 + PHP 8.3+
- **المطوّر:** عبد الرحمن المصطفى (مشروع تخرّج في هندسة البرمجيات)

---

## 2. تعريف المشروع (Definition)

نظام ويب داخلي (Intranet System) يُحوّل العمليات الإدارية اليومية للموارد البشرية — الحضور والانصراف، الرواتب، الإجازات، الوثائق الرسمية — من معاملات ورقية بطيئة إلى **بوابة رقمية موحّدة** قائمة على مبدأ **الخدمة الذاتية (Self-Service)**.

المبادئ الأساسية:
- **لكل موظف بوابته الخاصة:** يدير بياناته، يتابع مستحقاته، يوثّق طلباته بنفسه.
- **لكل مدير لوحته:** يراجع ويُعتمد طلبات فريقه مباشرة.
- **للإدارة سجل تدقيق كامل (Audit Trail):** من أضاف/عدّل/حذف، وماذا، ومتى، والقيم القديمة والجديدة.

النظام مبني كتطبيق Monolith (Blade + Tailwind CSS) خفيف وسريع النشر على أي استضافة PHP تقليدية، وبنيته جاهزة للتوسّع مستقبلاً نحو واجهة API/موبايل منفصلة.

**المشكلة التي يحلّها النظام:**

| قبل النظام | بعد النظام |
|---|---|
| طلبات إجازة عبر واتساب/ورق، بلا أرشفة | طلب رقمي موثّق بحالة (قيد المراجعة / موافق / مرفوض) مع مسار اعتماد واضح |
| حساب الرواتب يدوياً كل شهر | محرك رواتب يحتسب الصافي تلقائياً من الحضور + البدلات − الخصومات |
| متابعة الحضور بدفاتر/إكسل مبعثرة | ربط مباشر مع أجهزة البصمة واحتساب التأخير/الإضافي آلياً |
| ضياع وثائق الموظفين | أرشفة رقمية مركزية لكل وثيقة مرتبطة بتاريخ انتهاء |
| لا يوجد سجل لمن عدّل ماذا | Audit Log كامل لكل عملية إضافة/تعديل/حذف |

---

## 3. أهداف النظام (Objectives)

1. **أتمتة دورة حياة الموظف** بالكامل: تسجيل، حضور، طلبات، رواتب، وثائق.
2. **إلغاء المعاملات الورقية** عبر بوابة رقمية موحّدة مع أرشفة كاملة.
3. **احتساب الرواتب تلقائياً (Payroll Engine):** `الصافي = الأساسي + البدلات − الخصومات` اعتماداً على الحضور والوقوعات المعتمدة.
4. **ربط الحضور بأجهزة البصمة** مع احتساب آلي لدقائق التأخير والعمل الإضافي حسب وردية كل موظف (بما فيها الورديات الليلية).
5. **توفير سجل تدقيق (Audit Log)** يسجّل كل Create/Update/Delete مع القيم القديمة والجديدة.
6. **تمكين الخدمة الذاتية:** كل موظف يستخرج قسيمة راتبه وطلباته ووثائقه بنفسه بصيغة PDF عربية.
7. **تطبيق صلاحيات قائمة على الأدوار (RBAC):** فصل صارم بين الموظف والمدير والإدارة.
8. **حماية البيانات:** SoftDeletes، تعطيل الحسابات (`is_active`)، وقفل المعالجة المتزامنة (`lockForUpdate`).

---

## 4. مميزات النظام (Features)

- 🔐 **مصادقة وصلاحيات كاملة** — تسجيل دخول، تحقق بريد إلكتروني (Breeze)، ميدل وير صلاحيات لكل مسار.
- 🔢 **دخول برمز OTP** — نظام رموز تحقق مؤقتة (`OtpCode`) عبر البريد، مع قفل بعد محاولات فاشلة (5 افتراضياً) وحماية إعادة الإرسال.
- 🕐 **محرك حضور وانصراف** مرتبط بأجهزة بصمة، مع احتساب آلي لدقائق التأخير والإضافي حسب وردية الموظف وفترة السماح (Grace Period).
- 🌙 **دعم الورديات الليلية (Overnight Shifts)** — ورديات تعبر منتصف الليل (Evening/Night) عبر حقل `is_overnight`.
- 💰 **محرك رواتب (Payroll Engine)** يحسب الصافي تلقائياً ويولّد أوامر صرف شهرية للجميع دفعة واحدة.
- 📄 **تصدير PDF عربي كامل** (عبر mPDF مع RTL وربط الحروف) لقسائم الرواتب، ملفات الموظفين، تفاصيل الطلبات، والتقارير المالية.
- 📝 **دورة طلبات متكاملة (HR Transactions):** تقديم → مراجعة المدير → اعتماد/رفض → خصم أو إعادة رصيد الإجازة تلقائياً (مع `DB::transaction` و `lockForUpdate`).
- 📁 **أرشفة وثائق رقمية** لكل موظف (هوية/جواز/عقد/شهادة صحية) مع تتبّع تاريخ الانتهاء وفهرس فريد.
- 🏢 **إدارة كاملة** للأقسام، الورديات، العطل الرسمية (المتكررة سنوياً)، وأجهزة البصمة (حالة اتصال/مزامنة).
- 🧾 **سجل تدقيق (Audit Log)** يسجّل كل عملية مع القيم القديمة/الجديدة والزمن (`performed_at`).
- 🌓 **وضع داكن/فاتح (Dark/Light Theme)** محفوظ لكل مستخدم.
- 📱 **تصميم متجاوب بالكامل (Responsive)** مع قائمة جانبية مخصصة للهواتف.
- 🔔 **Modal تفاصيل الطلب** — عرض تفاصيل أي طلب بنافذة منبثقة بدون مغادرة الصفحة.
- 📊 **تقويم الطلبات** — عرض الوقوعات كأحداث ملوّنة حسب الحالة عبر FullCalendar.
- 📤 **تصدير CSV** للطلبات والتقارير المالية بترميز UTF-8 (BOM).
- 💬 **وحدة رسائل SMS** (مرتكزة على Notification) لإرسال رسائل فردية/لأقسام/للجميع.
- ⚡ **تحسينات أداء:** Cache للبيانات الثابتة (`departments.all`, `shifts.all`)، Pagination، SoftDeletes، Observers لحفظ Audit Log تلقائياً.

---

## 5. الأدوات والمتطلبات والمكتبات (Tools, Requirements & Libraries)

### 5.1 المتطلبات التشغيلية (Requirements)
| المتطلب | القيمة |
|---|---|
| PHP | 8.3+ |
| Composer | أحدث إصدار |
| Node.js / npm | 20+ |
| قاعدة البيانات | MySQL / MariaDB (متوافق مع SQLite للتطوير المحلي) |
| خادم الويب | أي استضافة PHP (التطوير: `php artisan serve`) |
| خطوط/مكتبة PDF | mPDF + خط `dejavusans` لدعم العربية |

### 5.2 المكتبات الخلفية (Backend — Composer `require`)
- `laravel/framework` ^13.8 — إطار العمل
- `laravel/sanctum` ^4.3 — مصادقة API (Token-based)
- `laravel/tinker` ^3.0 — أداة سطر الأوامر التفاعلية
- `mpdf/mpdf` ^8.3 — محرك تصدير PDF بدعم عربي/RTL

### 5.3 أدوات التطوير (Dev)
- `laravel/breeze` ^2.4 — توليد نظام المصادقة
- `fruitcake/laravel-debugbar` ^4.4 — تنقيح
- `laravel/pail` / `laravel/pao` — مراقبة اللوج
- `laravel/pint` ^1.27 — تنسيق الكود (PSR-12)
- `phpunit/phpunit` ^12.5 + `mockery/mockery` + `nunomaduro/collision` — اختبارات
- `fakerphp/faker` — بيانات وهمية للـ Seeders

### 5.4 الواجهة الأمامية (Frontend — `package.json`)
- **Blade Templates** + **Tailwind CSS** ^3.1 (+ `@tailwindcss/forms`)
- **Alpine.js** ^3.4 — تفاعلية خفيفة (Modals, Theme)
- **Vite** ^8 + `laravel-vite-plugin` — أداة البناء
- **Lucide Icons** — الأيقونات
- `autoprefixer` / `postcss` — معالجة CSS
- `concurrently` — تشغيل خادم + Vite + Queue + Pail معاً (`composer run dev`)

### 5.5 نصوص التشغيل (Composer Scripts)
- `composer setup` — تثبيت + `.env` + مفاتيح + migrate + npm build
- `composer run dev` — تشغيل server/queue/logs/vite بالتوازي
- `composer test` — `php artisan test`

---

## 6. واجهات المشروع (Interfaces / UI)

جميع الواجهات مبنية بـ Blade داخل `resources/views/`، وتُقسَّم إلى **لوحة إدارة** (محمية بصلاحيات) و**منطقة "my"** (خدمة ذاتية للموظف). القوالب الرئيسية:

| المجلد/الملف | الوصف |
|---|---|
| `layouts/` | التخطيط العام (شريط جانبي، وضع داكن/فاتح، قائمة جوال) |
| `dashboard.blade.php` | لوحة التحكم: وضع `admin` (مؤشرات شاملة + رسم أسبوعي) أو `employee` (بطاقة أداء + رسم ربع سنوي) |
| `employees/` | `index` (جدول + فلترة + فرز + Pagination)، `create`, `edit`, `show`, `pdf` |
| `attendance/` | `index` (إدارة)، `my_index` (حضوري + تسجيل دخول/خروج)، `edit` |
| `requests/` | `index` (جدول + فلاتر + تقويم + KPIs + Modal)، `create`, `show`, `pdf` |
| `payroll/` | `index` (أوامر الصرف)، `my_index`، `pdf_payslip` |
| `departments/`, `shifts/`, `holidays/`, `devices/` | CRUD لكل وحدة تنظيمية |
| `documents/` | `index`, `create`, `show`, `edit` (الإدارة + "وثائقي") |
| `reports/` | `index` (تقارير تشغيلية/مالية)، `financial_pdf` |
| `sms/` | `index`, `create` |
| `auth/` | `login`, `otp-login`, `otp-verify`, `register`, `reset-password`, `verify-email` |
| `profile/` | تعديل الملف الشخصي |
| `emails/` | قوالب البريد (`OtpMail`, `WelcomeMail`) |
| `components/` | مكوّنات Blade المُعاد استخدامها |
| `errors/` | صفحات الخطأ (403/404/500) |

**مكوّن الواجهة النشط حالياً:** `resources/views/requests/index.blade.php` — يعرض إحصائيات (الإجمالي/قيد المراجعة/معتمد/مرفوض)، جدول الطلبات مع فلاتر (الحالة/النوع/التاريخ/البحث)، تقويم FullCalendar، وزر "تفاصيل" يفتح Modal عبر Alpine.js.

---

## 7. قاعدة البيانات (Database)

قاعدة البيانات تعتمد **14 جدولاً أساسياً** (بالإضافة إلى `sessions`, `cache`, `jobs`) مع علاقات مترابطة، وجميع الجداول الأساسية تدعم **SoftDeletes**، وجميع الحقول العددية/التاريخية مُحوّلة عبر `$casts`.

### 7.1 العلاقة بين الجداول (Table Relationships)

```
roles_permissions (1) ──< (N) users (1) ──< (1) employees (1) ──< (N) documents
                                                     │
                                                     ├──< (N) attendance_logs >── (N) attendance_devices
                                                     │
                                                     ├──< (N) hr_transactions ──> (N) users (approved_by)
                                                     │
                                                     └──< (N) payroll_orders

shifts (1) ──< (N) employees
departments (1) ──< (N) employees
holidays (مستقل — جدول مرجعي للعطل)
audit_logs (N) ──> (1) users
system_settings (مستقل — مفاتيح/قيم)
otp_codes (N) ──> (1) users
```

| الجدول | العلاقة | عبر الحقل | سلوك الحذف |
|---|---|---|---|
| `roles_permissions` → `users` | 1:N | `users.role_id` | `restrictOnDelete` |
| `users` → `employees` | 1:1 | `employees.user_id` (unique) | `set null` |
| `shifts` → `employees` | 1:N | `employees.shift_id` | `restrictOnDelete` |
| `departments` → `employees` | 1:N | `employees.department_id` | `set null` |
| `employees` → `documents` | 1:N | `documents.employee_id` | `cascadeOnDelete` |
| `employees` → `attendance_logs` | 1:N | `attendance_logs.employee_id` | `cascadeOnDelete` |
| `attendance_devices` → `attendance_logs` | 1:N | `attendance_logs.device_id` | `set null` |
| `employees` → `hr_transactions` | 1:N | `hr_transactions.employee_id` | `cascadeOnDelete` |
| `users` → `hr_transactions` (المعتمد) | 1:N | `hr_transactions.approved_by` | `set null` |
| `employees` → `payroll_orders` | 1:N | `payroll_orders.employee_id` | `cascadeOnDelete` |
| `users` → `audit_logs` | 1:N | `audit_logs.user_id` | `set null` |
| `users` → `otp_codes` | 1:N | `otp_codes.user_id` | `cascadeOnDelete` |

**قيود فريدة:** `employees(user_id)`, `employees(national_id)`, `attendance_logs(employee_id, log_date)`, `payroll_orders(employee_id, salary_month)`, `documents(employee_id, document_type)` (مؤشر فريد مضاف لاحقاً).

### 7.2 Views والـ Routes

ملفات المسارات: `routes/web.php` (الواجهة)، `routes/api.php` (REST API)، `routes/auth.php` (المصادقة)، `routes/console.php` (أوامر Artisan).

**مجموعة الويب المحمية** `[auth, user.active]` في `web.php`:

| المسار | Controller@method | الاسم |
|---|---|---|
| `/` | `DashboardController@index` | `dashboard` |
| `/attendance` (index, check-in, check-out, edit, update, destroy) | `AttendanceWebController` | `attendance.*` |
| `/departments` (index/show + CRUD محمي `role:admin,manager`) | `DepartmentWebController` | `departments.*` |
| `/employees` (index/show + CRUD محمي، `/employees/{employee}/pdf`) | `EmployeeWebController` | `employees.*` |
| `/shifts` (index/show + CRUD محمي) | `ShiftWebController` | `shifts.*` |
| `/holidays` (index/calendar/show + CRUD محمي) | `HolidayWebController` | `holidays.*` |
| `/devices` (index/show + CRUD محمي) | `DeviceWebController` | `devices.*` |
| `/documents` (index/create/show/edit/update/destroy) | `DocumentWebController` | `documents.*` |
| `/requests` (index/create/store/show/destroy, `update_status` POST+PATCH, `export-csv`, `pdf`) | `RequestWebController` | `requests.*` |
| `/payroll` (index, `generate`, `{employeeId}/download-pdf`, `{payroll}/mark-paid`) | `PayrollWebController` | `payroll.*` |
| `/reports` (index, `financial-pdf`, `export-csv`) | `DashboardController` | `reports.*` |
| `/sms` (index/create/store) | `SmsMessageController` | `sms.*` |
| `/my/*` (حضوري، وثائقي، طلباتي — منطقة الخدمة الذاتية) | Web Controllers | `my.*` |
| `/profile` (edit/update/destroy) | `ProfileController` | `profile.*` |

**مسارات OTP العامة** `otp/`: `login` (نموذج)، `send`, `verify` (نموذج + معالجة)، `resend`.

**مسارات المصادقة** `auth.php`: `login`, `forgot-password`, `reset-password`, `register` (محمي `role:admin`)، `verify-email`, `confirm-password`, `password`, `logout`.

### 7.3 الـ Controllers الرئيسية (Main Controllers)

**ويب (`app/Http/Controllers/`):**
- `DashboardController` — `index` (لوحة حسب الدور)، `reports`، `downloadFinancialReportPdf`، `exportCsv`.
- `AttendanceWebController` — `index`, `myAttendance`, `store`/`storeMy` (حضور + حساب تأخير)، `checkOut`/`checkOutMy` (انصراف + حساب إضافي)، `edit`, `update`, `destroy`.
- `EmployeeWebController` — `index` (فلترة/فرز/Pagination + Cache)، `create`, `store`, `edit`, `update` (رفع صورة)، `destroy` (يمنع الحذف إن له سجلات)، `show`, `downloadPdf`.
- `DepartmentWebController`, `ShiftWebController`, `HolidayWebController`, `DeviceWebController`, `DocumentWebController` — CRUD قياسي لكل وحدة.
- `PayrollWebController` — `index`, `myPayroll`, `store` (توليد شهري + تسجيل Audit)، `downloadPayslipPdf`, `markAsPaid`.
- `RequestWebController` — `index` (إحصائيات + تقويم + فلاتر)، `create`, `store` (تحقق رصيد إجازة)، `updateStatus` (خصم/إعادة رصيد)، `destroy`, `downloadPdf`, `downloadCsv`.
- `SmsMessageController` — `index`, `create`, `store` (تجميع المستلمين حسب النوع).
- `OtpController` — `showLoginForm`, `sendOtp`, `showVerifyForm`, `verifyOtp`, `resendOtp` (مع قفل المحاولات).
- `ProfileController`, ومجلد `Auth/` (Breeze)، `OtpController`.

**API (`app/Http/Controllers/Api/`):** `AuthController`, `EmployeeController`, `DepartmentApiController`, `ShiftController`, `HolidayController`, `DocumentController`, `AttendanceDeviceController`, `AttendanceLogController`, `HrTransactionController`, `PayrollOrderController`, `AuditLogController`, `RolePermissionController`, `SystemSettingController`.

**Middleware مخصص:** `RoleMiddleware` (`role:admin,manager,...`)، `EnsureUserIsActive` (`user.active`).

**مكونات مساعدة:** `app/Http/Controllers/Traits/`، `app/Observers/` (`ShiftObserver`, `DepartmentObserver`, `HolidayObserver` — تسجيل Audit تلقائي)، `app/Policies/`, `app/Mail/` (`OtpMail`, `WelcomeMail`)، `app/Notifications/` (`CustomMessageNotification`)، `app/Packages/`.

### 7.4 الـ Models (14 نموذج)

#### `RolePermission` (الجدول `roles_permissions`)
`id`, `role_name` (unique), `description` — العلاقة: `users()` 1:N.

#### `User` (الجدول `users`)
`id`, `name`, `email` (unique), `password`, `avatar`, `role_id` (FK), `is_active` (tinyint 1/0), `remember_token` — يستخدم `HasApiTokens` (Sanctum). العلاقات: `role()`، `employee()` (1:1)، `approvedTransactions()`، `auditLogs()`.

#### `Employee` (الجدول `employees`)
الحقول (أهمها): `user_id` (unique, nullable), `shift_id`, `department_id`, `first_name`, `last_name`, `national_id` (unique), `phone`, `base_salary` (decimal 15,2), `bank_account_iban`, `join_date`, `resign_date`, `vacation_balance` (int, افتراضي 21), `performance_score` (decimal 3,2), `job_title`, `education_level` (enum), `marital_status` (enum), `nationality`, `address`, `emergency_contact_*`, `date_of_birth`, `place_of_birth`, `contract_end_date`, `insurance_number`, `last_promotion_date`, `avatar`. **Accessors:** `age`, `education_label`, `marital_status_label`, `full_name`. العلاقات: `user()`, `shift()`, `department()`, `documents()`, `attendanceLogs()`, `hrTransactions()`, `payrollOrders()`.

#### `Shift` (الجدول `shifts`)
`shift_name`, `start_time` (time), `end_time` (time), `grace_period_minutes` (int, افتراضي 15), `is_overnight` (bool). العلاقة: `employees()` 1:N + `ShiftObserver`.

#### `Department` (الجدول `departments`)
`name` (unique), `description`. العلاقة: `employees()` 1:N + `DepartmentObserver`.

#### `Holiday` (الجدول `holidays`)
`holiday_name`, `start_date`, `end_date`, `is_recurring` (tinyint). + `HolidayObserver`.

#### `AttendanceDevice` (الجدول `attendance_devices`)
`device_name`, `ip_address` (45), `location`, `status` (enum online/offline)، `last_seen_at`, `last_sync_at`, `last_sync`. العلاقة: `attendanceLogs()` 1:N.

#### `AttendanceLog` (الجدول `attendance_logs`)
`employee_id`, `device_id` (nullable), `log_date` (date), `check_in`, `check_out`, `late_minutes` (int), `overtime_minutes` (int), `status` (enum present/absent/late/holiday). **مؤشر فريد** `(employee_id, log_date)`. العلاقات: `employee()`, `device()`.

#### `HrTransaction` (الجدول `hr_transactions`)
`employee_id`, `transaction_type` (enum leave/permission/promotion/penalty/transfer), `start_date_time`, `end_date_time`, `description`, `financial_impact` (decimal 10,2), `status` (enum pending/approved/rejected)، `approved_by` (FK nullable). العلاقات: `employee()`, `approver()` (User).

#### `Document` (الجدول `documents`)
`employee_id`, `document_type` (enum identity/passport/contract/health_certificate), `document_number`, `expiry_date`, `file_path`. **مؤشر فريد** `(employee_id, document_type)`. العلاقة: `employee()`.

#### `PayrollOrder` (الجدول `payroll_orders`)
`employee_id`, `salary_month` (YYYY-MM), `allowances` (decimal 15,2), `deductions` (decimal 15,2), `net_salary` (decimal 15,2), `payment_status` (enum draft/approved/paid)، `paid_at`. **مؤشر فريد** `(employee_id, salary_month)`. العلاقة: `employee()`.

#### `AuditLog` (الجدول `audit_logs`)
`user_id` (nullable), `action_type` (enum create/update/delete)، `table_name`, `record_id`, `old_values` (JSON/text)، `new_values` (JSON/text)، `performed_at` (يستخدم `useCurrent`، ويعطّل `$timestamps`). العلاقة: `user()`.

#### `SystemSetting` (الجدول `system_settings`)
`setting_key` (unique), `setting_value`. (مثال: `company_name` = "المنقذ").

#### `OtpCode` (الجدول `otp_codes`)
`email`, `user_id` (nullable), `code` (مُجزّأ), `type` (افتراضي login)، `expires_at` (5 دقائق)، `used_at`, `failed_attempts`, `locked_until`. العلاقة: `user()` + `isValid()`.

### 7.5 الـ APIs (REST Endpoints)

محمية بـ `Authenticate` (Sanctum) + `user.active` في `routes/api.php`، وتُرجع JSON منسّق (`data`).

**المصادقة:**
- `POST /api/login` (عام) — يُرجع `token` عبر `createToken`.
- `POST /api/logout` — حذف التوكن الحالي.
- `GET /api/me` — بيانات المستخدم + الدور + ملف الموظف.

**الموارد (apiResource مع بادئة `api.`):**
- `/api/departments` — index/show/store/update/destroy
- `/api/shifts` — index/show/store/update/destroy
- `/api/holidays` — index/show/store/update/destroy
- `/api/employees` — index/show/store/update/destroy
- `/api/documents` — index/show/store/update/destroy

**مخصصة:**
- `GET/POST /api/devices`، `GET/PUT/DELETE /api/devices/{id}`، `PATCH /api/devices/{id}/toggle-status`
- `GET /api/roles`، `GET /api/roles/{id}`
- `GET /api/settings`، `PUT /api/settings`
- `GET /api/documents/expiring/{days?}` — الوثائق قرب الانتهاء
- `GET /api/attendance-logs`، `POST /api/attendance-logs/check-in`، `PATCH /api/attendance-logs/{id}/check-out`، `GET /api/attendance-logs/daily/{date}`
- `GET /api/hr-transactions`، `POST /api/hr-transactions` (تقديم)، `POST /api/hr-transactions/{id}/process-approval`
- `GET /api/payroll-orders`، `POST /api/payroll-orders/generate/{salaryMonth}`، `GET /api/payroll-orders/employee/{employeeId}`
- `GET /api/audit-logs`

### 7.6 ملاحظات إضافية (Additional Notes)

- **الأدوار الفعلية:** بعد دمج `hr`/`investor` ضمن `admin` (مايجريشن `consolidate_roles` في 2026‑07‑11)، الأدوار المتبقية هي **`admin`, `manager`, `employee`** فقط (مُخزّنة في `roles_permissions`).
- **الأمان:** `is_active` لتعطيل الحسابات، `SoftDeletes` لحماية البيانات (مضافة عبر مايجريشنَين في 2026‑07‑11 و2026‑07‑12)، `lockForUpdate()` عند معالجة الطلبات لمنع التعارض، و`AuditLog` مُسجَّل عبر Observers (Shift/Department/Holiday) وعبر يدوي في بقية الـ Controllers.
- **منع حذف الموظف:** `EmployeeWebController@destroy` يرفض الحذف إن كان له سجلات حضور/طلبات/رواتب، وينصح بتعيين `resign_date`.
- **حساب الرواتب:** `lateDeductions = (lateMinutes/60) × (base_salary/30/8)`، ويُجمع مع خصومات `penalty` المعتمدة، ويُطرح من `base + allowances(promotion)` لإنتاج `net_salary`؛ يُنشأ بـ `payment_status = draft`.
- **الورديات الليلية:** حقل `is_overnight` يدعم الورديات التي تعبر منتصف الليل (أضيف في 2026‑07‑11).
- **تعديل دقّة الرواتب:** مايجريشن `update_payroll_orders_decimals` (2026‑07‑07) رفع خانات `allowances/deductions/net_salary` إلى `decimal(15,2)`.
- **قائمة الانتظار (Queue):** معرّفة في `composer run dev` (`queue:listen`) لكن توليد الرواتب حالياً متزامن (مخطط نقله لـ Queue لمعالجة أعداد كبيرة).
- **الإشعارات SMS:** وحدة `SmsMessageController` موجودة والمنطق يعمل، لكن استدعاء `Notification::route('sms', ...)` **مُعلّق بالتعليق** (معطّل حالياً) — بانتظار بوابة SMS.
- **البريد:** `OtpMail` و`WelcomeMail` يعملان عبر `Mail::to(...)->send(...)`.
- **الاختبارات:** PHPUnit مُهيّأ لكن تغطية Feature Tests للمسارات الحساسة (الرواتب/الاعتماد) مخطط لها لاحقاً.
- **Seeders:** 14 مُولّد بيانات (Users, Employees=20, Departments, Shifts, Holidays, Devices, Documents, AttendanceLogs, HrTransactions, PayrollOrders, AuditLogs, Roles, SystemSettings). حسابات تجريبية: `admin@hr.com`, `hr@hr.com` (كلمة المرور `password`).

---

## 8. تصدير تقارير PDF (PDF Reports Export)

النظام يعتمد مكتبة **mPDF** (`mpdf/mpdf`) لتوليد تقارير PDF بدعم كامل للغة العربية واتجاه RTL، بالإعدادات الموحّدة:

```php
new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'default_font' => 'dejavusans',   // خط يدعم تشكيل الحروف العربية
]);
```

يُمرَّر قالب Blade عبر `view('...')->render()` ثم `WriteHTML()`، ويُعاد الملف للتنزيل بـ `Output($filename, 'D')` وترويسات `Content-Type: application/pdf`.

**نقاط التصدير المتاحة:**

| المسار | الوصف | الـ Controller@method | قالب Blade |
|---|---|---|---|
| `GET /payroll/{employeeId}/download-pdf?month=` | قسيمة راتب (Payslip) للموظف | `PayrollWebController@downloadPayslipPdf` | `payroll/pdf_payslip` |
| `GET /employees/{employee}/pdf` | ملف الموظف الكامل PDF | `EmployeeWebController@downloadPdf` | `employees/pdf` |
| `GET /requests/{transaction}/pdf` | تفاصيل الطلب PDF | `RequestWebController@downloadPdf` | `requests/pdf` |
| `GET /reports/financial-pdf` | التقرير المالي الشامل PDF | `DashboardController@downloadFinancialReportPdf` | `reports/financial_pdf` |

**إجراءات الحماية المطبّقة:**
- التحقق من وجود كلاس mPDF (`ensureMpdfAvailable`) قبل التوليد.
- فرض الصلاحيات: مثلاً لا يمكن للموظف تصدير طلب لا يخصه (`abort(403)` في `RequestWebController@downloadPdf`).
- استخدام اسم الشركة من `SystemSetting` (`company_name`) في رأس القسيمة.

**تصدير مكمِّل (CSV):** التقارير والطلبات تدعم تصدير CSV بترميز UTF-8 (BOM) لفتح صحيح في Excel العربي:
- `GET /requests/export-csv` و `GET /my/requests/export-csv` (طلبات)
- `GET /reports/export-csv` (رواتب شهرية)

---

## 9. سيناريوهات استخدام رئيسية (Use-Case Summary)

1. **الموظف يقدّم إجازة:** `/my/requests/create` → `RequestWebController@store` (يتحقق من `vacation_balance`) → الحالة `pending` + تسجيل Audit.
2. **المدير يعتمد:** `requests.update_status` → تحقق القسم + `lockForUpdate` + خصم الرصيد عند الاعتماد + Audit.
3. **الإدارة تولّد الرواتب:** `payroll.generate` → حلقة على الموظفين النشطين + احتساب التأخير/العقوبات/الترقيات → `PayrollOrder` بحالة `draft` + Audit.
4. **تسجيل حضور:** `attendance.check-in` → مقارنة بوردية الموظف + فترة السماح → `late_minutes`/الحالة.
5. **تصدير قسيمة:** `payroll.download_pdf` → قالب `pdf_payslip` + mPDF → تنزيل RTL.
6. **دخول OTP:** `otp.send` → بريد الرمز (5 دقائق) → `otp.verify` (مع قفل بعد 5 محاولات فاشلة).

---

*نهاية الوثيقة — مبنية على فحص شيفرة المصدر في 2026-07-12.*
