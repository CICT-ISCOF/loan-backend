<?php

namespace App\Traits;

use App\Models\Log;

trait HasLogs
{
    public function logs()
    {
        return $this->morphMany(Log::class, 'loggable');
    }
}
