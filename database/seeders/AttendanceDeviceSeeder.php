<?php

namespace Database\Seeders;

use App\Models\AttendanceDevice;
use Illuminate\Database\Seeder;

class AttendanceDeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            [
                'device_name' => 'Main Entrance Device',
                'ip_address' => '192.168.1.100',
                'status' => 'online',
                'location' => 'Main Entrance',
                'last_seen_at' => now()->subHours(2),
            ],
            [
                'device_name' => 'Side Gate Device',
                'ip_address' => '192.168.1.101',
                'status' => 'offline',
                'location' => 'Side Gate',
                'last_seen_at' => now()->subDays(1),
            ],
        ];

        foreach ($devices as $device) {
            AttendanceDevice::firstOrCreate(
                ['device_name' => $device['device_name']],
                $device
            );
        }
    }
}
