<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use App\Models\Shift;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $shifts = Shift::withCount('employees')->paginate(15)->appends($request->query());

        return $this->successResponse('تم جلب الورديات بنجاح', $shifts);
    }

    public function store(StoreShiftRequest $request)
    {
        $shift = Shift::create($request->validated());

        $this->logAudit('create', 'shifts', $shift->id, null, $shift->toArray());

        return $this->successResponse('تم إنشاء الوردية بنجاح', $shift, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $shift = Shift::withCount('employees')->findOrFail($id);

        return $this->successResponse('تم جلب تفاصيل الوردية بنجاح', $shift);
    }

    public function update(UpdateShiftRequest $request, $id)
    {
        $shift = Shift::findOrFail($id);
        $oldValues = $shift->toArray();

        $shift->update($request->validated());

        $this->logAudit('update', 'shifts', $shift->id, $oldValues, $shift->fresh()->toArray());

        return $this->successResponse('تم تحديث الوردية بنجاح', $shift);
    }

    public function destroy($id)
    {
        $shift = Shift::withCount('employees')->findOrFail($id);

        if ($shift->employees_count > 0) {
            return $this->errorResponse('لا يمكن حذف هذه الوردية لارتباط موظفين بها حالياً.', Response::HTTP_CONFLICT);
        }

        $shiftData = $shift->toArray();
        $shift->delete();

        $this->logAudit('delete', 'shifts', $shift->id, $shiftData, null);

        return $this->successResponse('تم حذف الوردية بنجاح');
    }

    private function logAudit(string $action, string $tableName, int $recordId, ?array $oldValues, ?array $newValues): void
    {
        \App\Models\AuditLog::create([
            'user_id' => Auth::id(),
            'action_type' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'performed_at' => now(),
        ]);
    }
}
