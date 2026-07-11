<?php

namespace App\Http\Controllers\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    protected function audit(string $actionType, object $model, ?array $oldValues = null): void
    {
        AuditLog::create([
            'user_id'      => Auth::id(),
            'action_type'  => $actionType,
            'table_name'   => $model->getTable(),
            'record_id'    => $model->id,
            'old_values'   => $oldValues,
            'new_values'   => $actionType === 'delete' ? null : $model->fresh()->toArray(),
            'performed_at' => now(),
        ]);
    }
}
