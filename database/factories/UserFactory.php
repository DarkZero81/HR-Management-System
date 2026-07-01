<?php

namespace Database\Factories;

use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role_id' => RolePermission::inRandomOrder()->first()?->id ?? 4,
            'is_active' => true,
        ];
    }
}