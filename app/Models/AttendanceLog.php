<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'device_id',
        'log_date',
        'check_in',
        'check_out',
        'late_minutes',
        'overtime_minutes',
        'status'
    ];

    protected $casts = [
        'log_date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'late_minutes' => 'integer',
        'overtime_minutes' => 'integer'
    ];

    /**
     * علاقة سجل الحضور بالموظف.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * علاقة سجل الحضور بجهاز البصمة الذي سُحبت منه الحركة.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(AttendanceDevice::class, 'device_id');
    }
}
