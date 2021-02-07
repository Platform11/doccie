<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Account extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function users(): HasMany {
        return $this->hasMany('App\Models\User');
    }

    public function administrations(): HasMany {
        return $this->hasMany('App\Models\Administration');
    }

    public function admin(): BelongsTo {
        return $this->BelongsTo('App\Models\User');
    }
}
