<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'integer',
        'password' => 'hashed',
    ];

    /**
     * علاقة المستخدم بالصلاحية التابع لها.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(RolePermission::class, 'role_id');
    }

    /**
     * علاقة الحساب بملف الموظف (ذاتية الموظف الذكية).
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    /**
     * علاقة المدير أو مسؤول الـ HR بالطلبات والوقوعات التي قام باعتمادها أو رفضها.
     */
    public function approvedTransactions(): HasMany
    {
        return $this->hasMany(HrTransaction::class, 'approved_by');
    }

    /**
     * علاقة المستخدم بالعمليات الإدارية التي قام بها (لأغراض تقرير الأمان).
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }
}
