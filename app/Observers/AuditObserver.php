<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->log('created', $model, [], $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $this->log('updated', $model, $model->getOriginal(), $model->getChanges());
    }

    public function deleted(Model $model): void
    {
        $this->log('deleted', $model, $model->getOriginal(), []);
    }

    private function log(string $action, Model $model, array $oldValues, array $newValues): void
    {
        // Exclude audit_logs table to prevent infinite loops
        if ($model instanceof AuditLog) {
            return;
        }

        // Remove timestamps from logged values
        $exclude = ['created_at', 'updated_at', 'deleted_at', 'remember_token', 'password', 'two_factor_secret', 'two_factor_recovery_codes'];
        $oldValues = array_diff_key($oldValues, array_flip($exclude));
        $newValues = array_diff_key($newValues, array_flip($exclude));

        try {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => $action,
                'model_type' => get_class($model),
                'model_id'   => $model->getKey(),
                'old_values' => $oldValues ?: null,
                'new_values' => $newValues ?: null,
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Never let audit logging break the application
            \Illuminate\Support\Facades\Log::warning('AuditLog write failed: ' . $e->getMessage());
        }
    }
}
