<?php

namespace App\Models;

use Database\Factories\AttendanceDeviceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_name',
        'ip_address',
        'status',
        'last_sync',
    ];

    protected function casts(): array
    {
        return [
            'last_sync' => 'datetime',
        ];
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class, 'device_id');
    }
}