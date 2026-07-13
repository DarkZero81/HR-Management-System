<?php

namespace App\Http\Controllers\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

/**
 * Auditable trait for automatic audit logging.
 *
 * Provides a reusable method to log create/update/delete actions
 * to the audit_logs table with old and new values.
 *
 * Usage:
 *   use Auditable;
 *   $this->audit('create', $model);
 *   $this->audit('update', $model, $oldValues);
 *   $this->audit('delete', $model, $oldValues);
 */
trait Auditable
{
    /**
     * Create an audit log entry for the given model action.
     *
     * @param  string  $actionType  The action type: 'create', 'update', or 'delete'
     * @param  object  $model  The Eloquent model instance
     * @param  array|null  $oldValues  The original values before the change (for update/delete)
     * @return void
     */
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
