<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HrTransaction extends Model
{
    use HasFactory;

    protected $table = 'hr_transactions';

    protected $fillable = [
        'employee_id',
        'transaction_type',
        'start_date_time',
        'end_date_time',
        'description',
        'financial_impact',
        'status',
        'approved_by'
    ];

    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'financial_impact' => 'decimal:2'
    ];

    /**
     * علاقة الحركة أو الطلب بالموظف مقدم الطلب.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * علاقة الطلب بالمستخدم (المدير أو الـ HR) المسؤول عن اعتماده أو رفضه.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
