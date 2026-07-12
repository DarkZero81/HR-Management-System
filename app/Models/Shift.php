<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'grace_period_minutes',
        'is_overnight'
    ];

    protected $casts = [
        'grace_period_minutes' => 'integer',
        'is_overnight' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::observe(\App\Observers\ShiftObserver::class);
    }

    /**
     * علاقة خطة الدوام (الوردية) بالموظفين المدرجين تحتها.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'shift_id');
    }
}
