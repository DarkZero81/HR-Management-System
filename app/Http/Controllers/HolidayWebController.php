<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HolidayWebController extends Controller
{
    public function index(): View
    {
        $holidays = Holiday::orderBy('start_date', 'desc')->paginate(15);
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

        Holiday::create($validated);
        return redirect()->route('holidays.index')->with('success', 'Holiday created');
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

        $holiday->update($validated);
        return redirect()->route('holidays.index')->with('success', 'Holiday updated');
    }

    public function destroy(Holiday $holiday): RedirectResponse
    {
        $holiday->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted');
    }
}
