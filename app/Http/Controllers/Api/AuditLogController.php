<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = AuditLog::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('table_name', 'like', "%{$search}%")
                  ->orWhere('action_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('action_type')) {
            $query->where('action_type', $request->action_type);
        }

        if ($request->filled('table_name')) {
            $query->where('table_name', $request->table_name);
        }

        $logs = $query->orderBy('performed_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json(['data' => $logs], 200);
    }
}