<?php

namespace App\Models;

use Database\Factories\HrTransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HrTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'transaction_type',
        'start_date_time',
        'end_date_time',
        'description',
        'financial_impact',
        'status',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date_time' => 'datetime',
            'end_date_time' => 'datetime',
            'financial_impact' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}