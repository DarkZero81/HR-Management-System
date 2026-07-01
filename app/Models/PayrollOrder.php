<?php

namespace App\Models;

use Database\Factories\PayrollOrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'allowances' => 'decimal:2',
            'deductions' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}