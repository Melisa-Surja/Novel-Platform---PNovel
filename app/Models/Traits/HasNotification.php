<?php

namespace App\Models\Traits;

use App\Models\Notification;
use App\Models\Series;

trait HasNotification
{
    /**
     * Boot the trait
     *
     * @return void
     */
    static protected function bootHasNotification()
    {
        static::deleted(function ($model) {
            // Delete all notifications with this
            $model->notifications()->delete();
        });
    }

    public function notifications() {
        return $this->morphMany(Notification::class, 'notification');
    }
}