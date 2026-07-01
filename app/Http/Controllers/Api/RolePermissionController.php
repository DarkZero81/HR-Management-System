<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\JsonResponse;

class RolePermissionController extends Controller
{
    public function index(): JsonResponse
    {
        $roles = RolePermission::all();
        return response()->json(['data' => $roles], 200);
    }

    public function show(int $id): JsonResponse
    {
        $role = RolePermission::findOrFail($id);
        return response()->json(['data' => $role], 200);
    }
}