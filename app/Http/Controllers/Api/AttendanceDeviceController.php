<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDevice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceDeviceController extends Controller
{
    public function index(): JsonResponse
    {
        $devices = AttendanceDevice::all();
        return response()->json(['data' => $devices], 200);
    }

    public function store(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'device_name' => ['required', 'string', 'max:100'],
                'ip_address' => ['required', 'string', 'max:45'],
                'status' => ['sometimes', 'in:online,offline'],
            ]);

            $device = AttendanceDevice::create($validated);
            return response()->json(['data' => $device], 201);
        });
    }

    public function show(int $id): JsonResponse
    {
        $device = AttendanceDevice::findOrFail($id);
        return response()->json(['data' => $device], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $device = AttendanceDevice::findOrFail($id);

            $validated = $request->validate([
                'device_name' => ['sometimes', 'string', 'max:100'],
                'ip_address' => ['sometimes', 'string', 'max:45'],
                'status' => ['sometimes', 'in:online,offline'],
            ]);

            $device->update($validated);
            return response()->json(['data' => $device], 200);
        });
    }

    public function destroy(int $id): JsonResponse
    {
        return DB::transaction(function () use ($id) {
            $device = AttendanceDevice::findOrFail($id);
            $device->delete();
            return response()->json(['message' => 'Device deleted successfully'], 200);
        });
    }

    public function toggleStatus(int $id): JsonResponse
    {
        return DB::transaction(function () use ($id) {
            $device = AttendanceDevice::findOrFail($id);
            $device->status = $device->status === 'online' ? 'offline' : 'online';
            $device->last_sync = $device->status === 'online' ? now() : null;
            $device->last_sync_at = $device->status === 'online' ? now() : null;
            $device->save();
            return response()->json(['data' => $device], 200);
        });
    }
}