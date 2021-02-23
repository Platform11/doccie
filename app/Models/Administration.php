<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\ModelStatus\HasStatuses;
use Spatie\ModelStatus\Status;

class Administration extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasStatuses;

    protected $casts = [
        'reports_to_include_in_overview' => 'array',
    ];

    protected $fillable = [
        'name',
        'code',
        'call_posts_code',
        'reports_to_include_in_overview',
        'relation_manager_id',
        'contact_first_name',
        'contact_last_name',
        'contact_email',
        'account_id',
    ];

    protected $appends = [
        'last_overview_sent_at',
    ];

    public function getUpdatedAtAttribute($value)
    {   
        $time_string = strtotime($value);

        return date('d-m-Y - H:i', $time_string);
    }

    public function getLastOverviewSentAtAttribute()
    {   
        $last_overview = $this->last_overview;

        if(empty($last_overview))
        {
            return null;
        }

        return $last_overview->sent_at;
    }

    public function account(): BelongsTo {
        return $this->belongsTo('App\Models\Account');
    }

    public function relation_manager(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    public function overviews(): HasMany {
        return $this->hasMany('App\Models\Overview')->orderByDesc('id');
    }

    public function last_overview()
    {
        return $this->hasOne('App\Models\Overview')->orderByDesc('id');
    }

    public function lastOverview()
    {
        return $this->belongsTo('App\Models\Overview');
    }

    public function scopeWithLastOverview($query)
    {
        $query->addSelect(['last_overview_id' => Overview::select('id')
            ->whereColumn('administration_id', 'administrations.id')
            ->orderByDesc('id')
            ->take(1)
        ])->with('lastOverview.last_status');
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
}
