<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDevice;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for attendance devices management.
 *
 * Handles:
 * - CRUD operations for fingerprint/attendance devices
 * - Audit logging for all device changes
 * - Prevents deletion of devices with associated attendance logs
 */
class DeviceWebController extends Controller
{
    /**
     * Display a listing of all attendance devices.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $devices = AttendanceDevice::orderBy('device_name')->paginate(15);
        return view('devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new device.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('devices.create');
    }

    /**
     * Store a newly created device in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
            'action_type' => 'create',
            'table_name'  => 'attendance_devices',
            'record_id'   => $device->id,
            'new_values'  => $device->toArray(),
            'performed_at'=> now(),
        ]);

        return redirect()->route('devices.index')->with('success', 'تم إضافة جهاز البصمة بنجاح.');
    }

    /**
     * Show the form for editing the specified device.
     *
     * @param  \App\Models\AttendanceDevice  $device
     * @return \Illuminate\View\View
     */
    public function edit(AttendanceDevice $device): View
    {
        return view('devices.edit', compact('device'));
    }

    /**
     * Update the specified device in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AttendanceDevice  $device
     * @return \Illuminate\Http\RedirectResponse
     */
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
            'action_type' => 'update',
            'table_name'  => 'attendance_devices',
            'record_id'   => $device->id,
            'old_values'  => $oldValues,
            'new_values'  => $device->fresh()->toArray(),
            'performed_at'=> now(),
        ]);

        return redirect()->route('devices.index')->with('success', 'تم تحديث بيانات جهاز البصمة بنجاح.');
    }

    /**
     * Remove the specified device from storage.
     *
     * Prevents deletion if attendance logs are associated with the device.
     *
     * @param  \App\Models\AttendanceDevice  $device
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(AttendanceDevice $device): RedirectResponse
    {
        if ($device->attendanceLogs()->exists()) {
            return redirect()->route('devices.index')->with('error', 'لا يمكن حذف الجهاز لوجود سجلات حضور مرتبطة به.');
        }

        $deviceData = $device->toArray();
        $device->delete();

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action_type' => 'delete',
            'table_name'  => 'attendance_devices',
            'record_id'   => $device->id,
            'old_values'  => $deviceData,
            'performed_at'=> now(),
        ]);

        return redirect()->route('devices.index')->with('success', 'تم إزالة الجهاز من النظام بنجاح.');
    }
}
