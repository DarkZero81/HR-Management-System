<?php

namespace Database\Factories;

use App\Models\AttendanceDevice;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceDeviceFactory extends Factory
{
    protected $model = AttendanceDevice::class;

    public function definition(): array
    {
        return [
            'device_name' => fake()->word() . ' Device',
            'ip_address' => fake()->ipv4(),
            'status' => fake()->randomElement(['online', 'offline']),
            'last_sync' => fake()->optional()->dateTime(),
        ];
    }
}