<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_id',
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
    ];

    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'performance_score' => 'decimal:2',
            'join_date' => 'date',
            'resign_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'employee_id');
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class, 'employee_id');
    }

    public function hrTransactions(): HasMany
    {
        return $this->hasMany(HrTransaction::class, 'employee_id');
    }

    public function payrollOrders(): HasMany
    {
        return $this->hasMany(PayrollOrder::class, 'employee_id');
    }
}