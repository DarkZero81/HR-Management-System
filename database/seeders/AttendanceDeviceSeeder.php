<?php

namespace Database\Seeders;

use App\Models\AttendanceDevice;
use Illuminate\Database\Seeder;

class AttendanceDeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            ['device_name' => 'Main Entrance Device', 'ip_address' => '192.168.1.100', 'status' => 'online'],
            ['device_name' => 'Side Gate Device', 'ip_address' => '192.168.1.101', 'status' => 'offline'],
        ];

        foreach ($devices as $device) {
            AttendanceDevice::create($device);
        }
    }
}