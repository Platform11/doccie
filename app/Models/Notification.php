<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    use HasFactory, LogsActivity;

    public function subject(): MorphTo {
        return $this->morphTo();
    }

    public function sender(): MorphTo {
        return $this->morphTo();
    }
}
