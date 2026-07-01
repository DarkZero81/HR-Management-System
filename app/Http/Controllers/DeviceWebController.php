<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDevice;
use Illuminate\View\View;

class DeviceWebController extends Controller
{
    public function index(): View
    {
        $devices = AttendanceDevice::orderBy('device_name')->paginate(15);
        return view('devices.index', compact('devices'));
    }
}
