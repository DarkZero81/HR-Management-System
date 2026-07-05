<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ShiftWebController extends Controller
{
    public function index(): View
    {
        $shifts = Shift::orderBy('shift_name')->paginate(8);
        return view('shifts.index', compact('shifts'));
    }

    public function create(): View
    {
        return view('shifts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // تم التعديل ليدعم صيغة الوقت من المتصفح مباشرة (ساعة:دقيقة) أو (ساعة:دقيقة:ثانية)
        $validated = $request->validate([
            'shift_name' => ['required', 'string', 'max:100'],
            'start_time' => ['required', 'date_format:H:i:s,H:i'],
            'end_time' => ['required', 'date_format:H:i:s,H:i', 'after:start_time'],
            'grace_period_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        $shift = Shift::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'create',
            'table_name' => 'shifts',
            'record_id' => $shift->id,
            'new_values' => $shift->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('shifts.index')->with('success', 'تم إنشاء الوردية بنجاح');
    }

    public function edit(Shift $shift): View
    {
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift): RedirectResponse
    {
        // تم التعديل ليدعم صيغ الوقت المختلفة عند التعديل منعاً لخطأ التحقق المكسور
        $validated = $request->validate([
            'shift_name' => ['required', 'string', 'max:100'],
            'start_time' => ['required', 'date_format:H:i:s,H:i'],
            'end_time' => ['required', 'date_format:H:i:s,H:i', 'after:start_time'],
            'grace_period_minutes' => ['nullable', 'integer', 'min:0'],
        ]);

        $oldValues = $shift->toArray();
        $shift->update($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'update',
            'table_name' => 'shifts',
            'record_id' => $shift->id,
            'old_values' => $oldValues,
            'new_values' => $shift->fresh()->toArray(),
            'performed_at' => now(),
        ]);

        return redirect()->route('shifts.index')->with('success', 'تم تحديث الوردية بنجاح');
    }

    public function destroy(Shift $shift): RedirectResponse
    {
        $shiftData = $shift->toArray();

        // قاعدة بيانات مقيدة بـ restrict لمنع الحذف إذا كان هناك موظفون على الوردية
        try {
            $shift->delete();
        } catch (\Exception $e) {
            return redirect()->route('shifts.index')->with('error', 'لا يمكن حذف هذه الوردية لارتباط موظفين بها حالياً.');
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => 'delete',
            'table_name' => 'shifts',
            'record_id' => $shift->id,
            'old_values' => $shiftData,
            'performed_at' => now(),
        ]);

        return redirect()->route('shifts.index')->with('success', 'تم حذف الوردية بنجاح');
    }
}
