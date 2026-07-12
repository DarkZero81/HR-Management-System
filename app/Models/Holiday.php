<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'holiday_name',
        'start_date',
        'end_date',
        'is_recurring'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_recurring' => 'integer'
    ];

    protected static function booted(): void
    {
        static::observe(\App\Observers\HolidayObserver::class);
    }
}
