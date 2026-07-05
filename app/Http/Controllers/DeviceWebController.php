<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDevice;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DeviceWebController extends Controller
{
    public function index(): View
    {
        $devices = AttendanceDevice::orderBy('device_name')->paginate(15);
        return view('devices.index', compact('devices'));
    }

    public function create(): View
    {
        return view('devices.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'device_name' => ['required', 'string', 'max:100'],
            'ip_address'  => ['required', 'ip'],
            'status'      => ['sometimes', 'in:online,offline'],
        ]);

        $device = AttendanceDevice::create($validated);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'INSERT',
            'table_name'  => 'attendance_devices',
            'record_id'   => $device->id,
            'new_values'  => json_encode($device->toArray(), JSON_UNESCAPED_UNICODE),
            'performed_at'=> now(),
        ]);

        return redirect()->route('devices.index')->with('success', 'تم إضافة جهاز البصمة بنجاح.');
    }

    public function edit(AttendanceDevice $device): View
    {
        return view('devices.edit', compact('device'));
    }

    public function update(Request $request, AttendanceDevice $device): RedirectResponse
    {
        $validated = $request->validate([
            'device_name' => ['required', 'string', 'max:100'],
            'ip_address'  => ['required', 'ip'],
            'status'      => ['sometimes', 'in:online,offline'],
        ]);

        $oldValues = $device->toArray();
        $device->update($validated);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'UPDATE',
            'table_name'  => 'attendance_devices',
            'record_id'   => $device->id,
            'old_values'  => json_encode($oldValues, JSON_UNESCAPED_UNICODE),
            'new_values'  => json_encode($device->fresh()->toArray(), JSON_UNESCAPED_UNICODE),
            'performed_at'=> now(),
        ]);

        return redirect()->route('devices.index')->with('success', 'تم تحديث بيانات جهاز البصمة بنجاح.');
    }

    public function destroy(AttendanceDevice $device): RedirectResponse
    {
        $deviceData = $device->toArray();

        try {
            $device->delete();
        } catch (\Exception $e) {
            return redirect()->route('devices.index')->with('error', 'لا يمكن حذف الجهاز لوجود سجلات حضور مرتبطة به.');
        }

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'DELETE',
            'table_name'  => 'attendance_devices',
            'record_id'   => $device->id,
            'old_values'  => json_encode($deviceData, JSON_UNESCAPED_UNICODE),
            'performed_at'=> now(),
        ]);

        return redirect()->route('devices.index')->with('success', 'تم إزالة الجهاز من النظام بنجاح.');
    }
}
