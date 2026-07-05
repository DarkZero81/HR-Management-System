<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class HolidayWebController extends Controller
{
    public function index(): View
    {
        $holidays = Holiday::orderBy('start_date', 'desc')->paginate(8);
        return view('holidays.index', compact('holidays'));
    }

    public function create(): View
    {
        return view('holidays.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'holiday_name' => ['required', 'string', 'max:150'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_recurring' => ['sometimes', 'boolean'],
        ]);

        $holiday = Holiday::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'create',
            'table_name' => 'holidays',
            'record_id' => $holiday->id,
            'new_values' => $holiday->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('holidays.index')->with('success', 'تم إنشاء الإجازة بنجاح');
    }

    public function edit(Holiday $holiday): View
    {
        return view('holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday): RedirectResponse
    {
        $validated = $request->validate([
            'holiday_name' => ['required', 'string', 'max:150'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_recurring' => ['sometimes', 'boolean'],
        ]);

        $oldValues = $holiday->toArray();
        $holiday->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'table_name' => 'holidays',
            'record_id' => $holiday->id,
            'old_values' => $oldValues,
            'new_values' => $holiday->fresh()->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('holidays.index')->with('success', 'تم تحديث الإجازة بنجاح');
    }

    public function destroy(Holiday $holiday): RedirectResponse
    {
        $holidayData = $holiday->toArray();
        $holiday->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'delete',
            'table_name' => 'holidays',
            'record_id' => $holiday->id,
            'old_values' => $holidayData,
            'performed_at' => now(),
        ]);

        return redirect()->route('holidays.index')->with('success', 'تم حذف الإجازة بنجاح');
    }
}
