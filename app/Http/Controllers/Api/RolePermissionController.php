<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\JsonResponse;

/**
 * API controller for role permissions management.
 *
 * Handles:
 * - Listing all roles
 * - Showing a specific role details
 */
class RolePermissionController extends Controller
{
    /**
     * Display a listing of all roles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $roles = RolePermission::all();
        return response()->json(['data' => $roles], 200);
    }

    /**
     * Display the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $role = RolePermission::findOrFail($id);
        return response()->json(['data' => $role], 200);
    }
}
