<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditLog extends Model
{
    use SoftDeletes;
    // تعطيل الـ timestamps الافتراضية لأننا نستخدم حقل performed_at مخصص في المايجريشن
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action_type',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'performed_at'
    ];

    protected $casts = [
        'old_values' => 'array', // يحول الـ JSON في قاعدة البيانات إلى Array برمجياً
        'new_values' => 'array',
        'performed_at' => 'datetime'
    ];

    /**
     * علاقة سجل العملية بالمستخدم الذي قام بها.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
