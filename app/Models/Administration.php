<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Administration extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'relation_manager_id',
        'contact_first_name',
        'contact_last_name',
        'contact_email',
        'account_id',
        'status',

    ];

    protected $appends = [
        'last_sent_report_date_time',
        'last_sent_report_author',
    ];

    public function getUpdatedAtAttribute($value)
    {   
        $time_string = strtotime($value);

        return date('d-m-Y - H:i', $time_string);
    }

    public function getLastSentReportDateTimeAttribute()
    {   
        if(!empty($this->reports()->latest()->first()))
        {
            return $this->reports()->latest()->first()->created_at;
        }

        return '';
    }

    public function getLastSentReportAuthorAttribute()
    {   
        if(!empty($this->reports()->latest()->first()))
        {   
            return $this->reports()->latest()->first()->author->first_name . ' ' . $this->reports()->latest()->first()->author->last_name;
        }

        return null;
    }

    public function account(): BelongsTo {
        return $this->belongsTo('App\Models\Account');
    }

    public function relation_manager(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    public function reports(): HasMany {
        return $this->hasMany('App\Models\Report')->orderByDesc('created_at');
    }
}
