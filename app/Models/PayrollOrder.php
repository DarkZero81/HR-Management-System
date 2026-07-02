<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayrollOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'salary_month',
        'allowances',
        'deductions',
        'net_salary',
        'payment_status',
        'paid_at'
    ];

    protected $casts = [
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_at' => 'datetime'
    ];

    /**
     * علاقة أمر صرف الراتب بالموظف المستحق.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
