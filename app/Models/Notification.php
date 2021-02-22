<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\ModelStatus\HasStatuses;
use Spatie\ModelStatus\Status;

class Notification extends Model
{
    use HasFactory, LogsActivity, HasStatuses;

    public function subject(): MorphTo {
        return $this->morphTo();
    }

    public function sender(): MorphTo {
        return $this->morphTo();
    }

    public function last_status()
    {
        return $this->morphOne('Spatie\ModelStatus\Status', 'model')->orderByDesc('id');
    }

    public function lastStatus()
    {
        return $this->belongsTo('Spatie\ModelStatus\Status');
    }

    public function scopeWithLastStatus($query)
    {
        $query->addSelect(['last_status_id' => Status::select('id')
            ->whereColumn('model_id', 'administrations.id')
            ->where('model_type', 'App\Models\Administration')
            ->orderByDesc('id')
            ->take(1)
        ])->with('lastStatus');
    }

    public function setDeliveryMetaData($delivery_meta_data)
    {   
        $this->delivery_meta_data = $delivery_meta_data;
        $this->save();
    }
}
