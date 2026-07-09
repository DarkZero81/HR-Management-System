<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_id',
        'department_id',
        'first_name',
        'last_name',
        'national_id',
        'phone',
        'base_salary',
        'bank_account_iban',
        'join_date',
        'resign_date',
        'vacation_balance',
        'performance_score',
        'avatar',
        'date_of_birth',
        'place_of_birth',
        'education_level',
        'marital_status',
        'nationality',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'job_title',
        'contract_end_date',
        'insurance_number',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'performance_score' => 'decimal:2',
        'vacation_balance' => 'integer',
        'join_date' => 'date',
        'resign_date' => 'date',
        'date_of_birth' => 'date',
        'contract_end_date' => 'date',
    ];

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    public function getEducationLabelAttribute(): string
    {
        return match($this->education_level) {
            'high_school' => 'ثانوية',
            'diploma' => 'دبلوم',
            'bachelor' => 'بكالوريوس',
            'master' => 'ماجستير',
            'phd' => 'دكتوراه',
            'other' => 'أخرى',
            default => '-',
        };
    }

    public function getMaritalStatusLabelAttribute(): string
    {
        return match($this->marital_status) {
            'single' => 'أعزب',
            'married' => 'متزوج',
            'divorced' => 'مطلق',
            'widowed' => 'أرمل',
            default => '-',
        };
    }

    /**
     * الحصول على الاسم الكامل للموظف.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * علاقة الموظف بحساب المستخدم الخاص به.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * علاقة الموظف بالوردية وخطة الدوام المحددة له.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    /**
     * علاقة الموظف بالقسم التابع له.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * علاقة الموظف بالوثائق والأوراق الثابتة الخاصة به.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'employee_id');
    }

    /**
     * علاقة الموظف بسجلات الدوام والحضور اليومية.
     */
    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class, 'employee_id');
    }

    /**
     * علاقة الموظف بجميع الوقوعات والطلبات الحركية (إجازات، أذونات، سلف).
     */
    public function hrTransactions(): HasMany
    {
        return $this->hasMany(HrTransaction::class, 'employee_id');
    }

    /**
     * علاقة الموظف بأوامر وكشوف صرف الرواتب الشهرية.
     */
    public function payrollOrders(): HasMany
    {
        return $this->hasMany(PayrollOrder::class, 'employee_id');
    }
}
