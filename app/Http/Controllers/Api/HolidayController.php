<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HolidayController extends Controller
{
    public function index(): JsonResponse
    {
        $holidays = Holiday::all();
        return response()->json(['data' => $holidays], 200);
    }

    public function store(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'holiday_name' => ['required', 'string', 'max:150'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
                'is_recurring' => ['sometimes', 'boolean'],
            ]);

            $holiday = Holiday::create($validated);
            return response()->json(['data' => $holiday], 201);
        });
    }

    public function show(int $id): JsonResponse
    {
        $holiday = Holiday::findOrFail($id);
        return response()->json(['data' => $holiday], 200);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $holiday = Holiday::findOrFail($id);

            $validated = $request->validate([
                'holiday_name' => ['sometimes', 'string', 'max:150'],
                'start_date' => ['sometimes', 'date'],
                'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
                'is_recurring' => ['sometimes', 'boolean'],
            ]);

            $holiday->update($validated);
            return response()->json(['data' => $holiday], 200);
        });
    }

    public function destroy(int $id): JsonResponse
    {
        return DB::transaction(function () use ($id) {
            $holiday = Holiday::findOrFail($id);
            $holiday->delete();
            return response()->json(['message' => 'Holiday deleted successfully'], 200);
        });
    }
}