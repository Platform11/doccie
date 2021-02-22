<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Account extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $appends = [
        'logo',
    ];

    public function getLogoAttribute()
    {   
        if(empty($this->getMedia('logo')->first()))
        {
            return asset('images/doccie-logo.png');
        }
        
        return $this->getMedia('logo')[0]->getFullUrl();
    }

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
