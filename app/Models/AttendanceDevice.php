<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_name',
        'ip_address',
        'location',
        'status',
        'last_sync_at',
        'last_seen_at',
        'last_sync'
    ];

    protected $casts = [
        'last_sync' => 'datetime',
        'last_sync_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    /**
     * علاقة الجهاز بسجلات الحضور اليومية التي تم سحبها من خلاله.
     */
    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class, 'device_id');
    }
}
