<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ShiftWebController extends Controller
{
    public function index(): View
    {
        $shifts = Shift::orderBy('shift_name')->paginate(15);
        return view('shifts.index', compact('shifts'));
    }

    public function create(): View
    {
        return view('shifts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'shift_name' => ['required', 'string', 'max:100'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
            'grace_period_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        Shift::create($validated);
        return redirect()->route('shifts.index')->with('success', 'Shift created');
    }

    public function edit(Shift $shift): View
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift): RedirectResponse
    {
        $validated = $request->validate([
            'shift_name' => ['required', 'string', 'max:100'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
            'grace_period_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        $shift->update($validated);
        return redirect()->route('shifts.index')->with('success', 'Shift updated');
    }

    public function destroy(Shift $shift): RedirectResponse
    {
        $shift->delete();
        return redirect()->route('shifts.index')->with('success', 'Shift deleted');
    }
}
