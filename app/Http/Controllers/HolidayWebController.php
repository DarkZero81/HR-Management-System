<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Controller for holidays management.
 *
 * Handles:
 * - CRUD operations for official holidays
 * - Holiday calendar view
 * - Support for recurring annual holidays
 * - Cache invalidation on changes
 * - Audit logging for all operations
 */
class HolidayWebController extends Controller
{
    /**
     * Display a paginated listing of all holidays.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $holidays = Holiday::orderBy('start_date', 'desc')->paginate(8);
        return view('holidays.index', compact('holidays'));
    }

    /**
     * Display the holiday calendar view.
     *
     * Holidays are cached for 1 hour and formatted for the calendar component.
     *
     * @return \Illuminate\View\View
     */
    public function calendar(): View
    {
        $holidays = Cache::remember('holidays.all', 3600, fn() => Holiday::all());

        if (! $holidays instanceof \Illuminate\Support\Collection) {
            $holidays = Holiday::all();
        }

        $holidays = $holidays->map(function ($holiday) {
            return [
                'id' => $holiday->id,
                'title' => $holiday->holiday_name,
                'start' => $holiday->start_date,
                'end' => $holiday->end_date ? \Carbon\Carbon::parse($holiday->end_date)->addDay()->format('Y-m-d') : null,
                'allDay' => true,
                'backgroundColor' => $holiday->is_recurring ? '#10b981' : '#3b82f6',
                'borderColor' => $holiday->is_recurring ? '#059669' : '#2563eb',
                'extendedProps' => [
                    'recurring' => $holiday->is_recurring,
                ],
            ];
        });

        return view('holidays.calendar', compact('holidays'));
    }

    /**
     * Show the form for creating a new holiday.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('holidays.create');
    }

    /**
     * Store a newly created holiday in storage.
     *
     * Invalidates the holidays cache after creation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'holiday_name' => ['required', 'string', 'max:150'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_recurring' => ['sometimes', 'boolean'],
        ]);

        $holiday = Holiday::create($validated);

        Cache::forget('holidays.all');

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

    /**
     * Show the form for editing the specified holiday.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\View\View
     */
    public function edit(Holiday $holiday): View
    {
        return view('holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified holiday in storage.
     *
     * Invalidates the holidays cache after update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\RedirectResponse
     */
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

        Cache::forget('holidays.all');

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

    /**
     * Remove the specified holiday from storage.
     *
     * Invalidates the holidays cache after deletion.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Holiday $holiday): RedirectResponse
    {
        $holidayData = $holiday->toArray();
        $holiday->delete();

        Cache::forget('holidays.all');

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
