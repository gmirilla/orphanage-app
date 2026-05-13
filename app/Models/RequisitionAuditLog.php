<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequisitionAuditLog extends Model
{
    protected $fillable = [
        'requisition_id', 'performed_by', 'action',
        'old_status', 'new_status', 'notes', 'ip_address',
    ];

    public static array $actionLabels = [
        'created'           => 'Requisition created',
        'updated'           => 'Requisition updated',
        'submitted'         => 'Submitted for approval',
        'approved'          => 'Approved',
        'rejected'          => 'Rejected — revision required',
        'document_uploaded' => 'Supporting document uploaded',
        'document_deleted'  => 'Supporting document removed',
        'resubmitted'       => 'Resubmitted after revision',
    ];

    public static array $actionIcons = [
        'created'           => 'plus-circle',
        'updated'           => 'pencil-square',
        'submitted'         => 'paper-airplane',
        'approved'          => 'check-circle',
        'rejected'          => 'x-circle',
        'document_uploaded' => 'paper-clip',
        'document_deleted'  => 'trash',
        'resubmitted'       => 'arrow-path',
    ];

    public static array $actionColors = [
        'created'           => 'text-blue-600 bg-blue-50',
        'updated'           => 'text-neutral-600 bg-neutral-100',
        'submitted'         => 'text-amber-600 bg-amber-50',
        'approved'          => 'text-green-600 bg-green-50',
        'rejected'          => 'text-red-600 bg-red-50',
        'document_uploaded' => 'text-indigo-600 bg-indigo-50',
        'document_deleted'  => 'text-neutral-500 bg-neutral-100',
        'resubmitted'       => 'text-amber-600 bg-amber-50',
    ];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class);
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function actionLabel(): string
    {
        return static::$actionLabels[$this->action] ?? ucfirst(str_replace('_', ' ', $this->action));
    }

    public function actionColor(): string
    {
        return static::$actionColors[$this->action] ?? 'text-neutral-600 bg-neutral-100';
    }

    public function actionIcon(): string
    {
        return static::$actionIcons[$this->action] ?? 'information-circle';
    }
}
