<?php

namespace App\Models;

use Database\Factories\RolePermissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RolePermission extends Model
{
    use HasFactory;

    protected $table = 'roles_permissions';

    protected $fillable = [
        'role_name',
        'description',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }
}