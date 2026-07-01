<?php

namespace Database\Factories;

use App\Models\RolePermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolePermissionFactory extends Factory
{
    protected $model = \App\Models\RolePermission::class;

    public function definition(): array
    {
        return [
            'role_name' => fake()->unique()->randomElement(['admin', 'investor', 'manager', 'employee']),
            'description' => fake()->sentence(),
        ];
    }
}