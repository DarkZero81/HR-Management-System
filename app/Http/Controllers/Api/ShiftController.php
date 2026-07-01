<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    public function index(): JsonResponse
    {
        $shifts = Shift::all();
        return response()->json(['data' => $shifts], 200);
    }

    public function store(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'shift_name' => ['required', 'string', 'max:100'],
                'start_time' => ['required', 'date_format:H:i:s'],
                'end_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
                'grace_period_minutes' => ['nullable', 'integer', 'min:0'],
            ]);

            $shift = Shift::create($validated);
            return response()->json(['data' => $shift], 201);
        });
    }

    public function show(int $id): JsonResponse
    {
        $shift = Shift::findOrFail($id);
        return response()->json(['data' => $shift], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $shift = Shift::findOrFail($id);

            $validated = $request->validate([
                'shift_name' => ['sometimes', 'string', 'max:100'],
                'start_time' => ['sometimes', 'date_format:H:i:s'],
                'end_time' => ['sometimes', 'date_format:H:i:s', 'after:start_time'],
                'grace_period_minutes' => ['sometimes', 'integer', 'min:0'],
            ]);

            $shift->update($validated);
            return response()->json(['data' => $shift], 200);
        });
    }

    public function destroy(int $id): JsonResponse
    {
        return DB::transaction(function () use ($id) {
            $shift = Shift::findOrFail($id);
            $shift->delete();
            return response()->json(['message' => 'Shift deleted successfully'], 200);
        });
    }
}