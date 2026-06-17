<?php

namespace App\Traits;

use App\Models\Log;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create', $model->getAttributes(), null);
        });

        static::updated(function ($model) {
            $old = $model->getOriginal();
            $new = $model->getChanges();
            $model->logActivity('update', $new, $old);
        });

        static::deleted(function ($model) {
            $model->logActivity('delete', null, $model->getOriginal());
        });
    }

    public function logActivity($action, $new = null, $old = null)
    {
        Log::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'table_name'  => $this->getTable(),
            'record_id'   => $this->id,
            'old_values'  => $old,
            'new_values'  => $new,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }
}