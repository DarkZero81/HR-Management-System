<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Controller for shift management.
 *
 * Handles:
 * - CRUD operations for work shifts
 * - Support for overnight shifts
 * - Shift statistics and employee counts
 * - Search functionality
 */
class ShiftWebController extends Controller
{
    /**
     * Display a listing of all shifts with search and stats.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $query = Shift::query()->withCount('employees');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('shift_name', 'like', '%' . $request->search . '%')
                  ->orWhere('start_time', 'like', '%' . $request->search . '%')
                  ->orWhere('end_time', 'like', '%' . $request->search . '%');
            });
        }

        $shifts = $query->orderBy('shift_name')->paginate(10)->appends($request->query());

        $shiftsWithCount = Shift::withCount('employees')->get();
        $maxEmployeesShift = $shiftsWithCount->sortByDesc('employees_count')->first();

        $stats = [
            'total' => Shift::count(),
            'total_employees' => $shiftsWithCount->sum('employees_count'),
            'avg_employees' => $shiftsWithCount->avg('employees_count'),
            'empty_shifts' => $shiftsWithCount->filter(fn($s) => $s->employees_count == 0)->count(),
            'max_employees_shift' => $maxEmployeesShift?->employees_count ?? 0,
            'max_employees_shift_name' => $maxEmployeesShift?->shift_name ?? null,
            'overnight_shifts' => Shift::where('is_overnight', true)->count(),
        ];

        return view('shifts.index', compact('shifts', 'stats'));
    }

    /**
     * Show the form for creating a new shift.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('shifts.form');
    }

    /**
     * Store a newly created shift in storage.
     *
     * @param  \App\Http\Requests\StoreShiftRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreShiftRequest $request): RedirectResponse
    {
        $shift = Shift::create($request->validated());

        return redirect()->route('shifts.index')->with('success', 'تم إنشاء الوردية بنجاح');
    }

    /**
     * Show the form for editing the specified shift.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\View\View
     */
    public function edit(Shift $shift): View
    {
        return view('shifts.form', compact('shift'));
    }

    /**
     * Update the specified shift in storage.
     *
     * @param  \App\Http\Requests\UpdateShiftRequest  $request
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateShiftRequest $request, Shift $shift): RedirectResponse
    {
        $shift->update($request->validated());

        return redirect()->route('shifts.index')->with('success', 'تم تحديث الوردية بنجاح');
    }

    /**
     * Remove the specified shift from storage.
     *
     * Prevents deletion if employees are assigned to the shift.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Shift $shift): RedirectResponse
    {
        if ($shift->employees()->exists()) {
            return redirect()->route('shifts.index')->with('error', 'لا يمكن حذف هذه الوردية لارتباط موظفين بها حالياً.');
        }

        $shift->delete();

        return redirect()->route('shifts.index')->with('success', 'تم حذف الوردية بنجاح');
    }

    /**
     * Display the specified shift with its employees.
     *
     * @param  \App\Models\Shift  $shift
     * @return \Illuminate\View\View
     */
    public function show(Shift $shift): View
    {
        $shift->load(['employees' => function ($query) {
            $query->orderByDesc('join_date');
        }]);

        return view('shifts.show', compact('shift'));
    }
}
