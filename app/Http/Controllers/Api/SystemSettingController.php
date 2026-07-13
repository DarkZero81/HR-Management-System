<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API controller for system settings management.
 *
 * Handles:
 * - Retrieving all system settings
 * - Bulk updating system settings
 */
class SystemSettingController extends Controller
{
    /**
     * Display all system settings.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $settings = SystemSetting::all();
        return response()->json(['data' => $settings], 200);
    }

    /**
     * Bulk update system settings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $settings = $request->validate([
                'settings' => ['required', 'array'],
                'settings.*.setting_key' => ['required', 'string', 'exists:system_settings,setting_key'],
                'settings.*.setting_value' => ['required', 'string'],
            ])['settings'];

            foreach ($settings as $setting) {
                SystemSetting::where('setting_key', $setting['setting_key'])
                    ->update(['setting_value' => $setting['setting_value']]);
            }

            return response()->json(['message' => 'Settings updated successfully'], 200);
        });
    }
}
