<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Spatie\ModelStatus\HasStatuses;


class Report extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia, HasStatuses;

    protected $casts = [
        'status' => 'array',
    ];

    protected $fillable = [
        'status',
        'overview_id',
        'type',
    ];

    public function getCreatedAtAttribute($value)
    {   
        $time_string = strtotime($value);

        return date('d-m-Y - H:i', $time_string);
    }

    public function getConfigurationAttribute()
    {   
        return $this->configuration();
    }

    public function overview(): BelongsTo {
        return $this->belongsTo('App\Models\Overview');
    }

    public function configuration()
    {    
        /*
            Need to implement an additional check in the future to determine the data
            provider (Twinfield, Exact etc.) For now we only support Twinfield so it's fine.
        */
        return match($this->type) {
            'unspecified_posts' => config('twinfield-reports.unspecified_posts'),
            'debtors' => config('twinfield-reports.debtors'),
            'creditors' => config('twinfield-reports.creditors'),
            default => throw new \Exception('report_type_is_unsupported'),
        };
    }

    public function visible_columns_pdf_export()
    {
        $configuration = $this->configuration();

        return collect($configuration['columns'])->filter(function($column) use ($configuration){
            return !collect($configuration['hidden_columns_in_pdf_export'])->contains($column['id']);
        })->values();
    }
}
