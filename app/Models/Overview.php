<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStatus\HasStatuses;
use App\Notifications\SendOverviewNotification;
use Spatie\ModelStatus\Status;
use App\Helpers\Reports\Twinfield\ReportComposer as TwinfieldReportComposer;
use App\Events\Overview\Composing\Started as OverviewComposingStarted;
use App\Events\Overview\Composing\Finished as OverviewComposingFinished;
use App\Events\Report\Composing\Started as ReportComposingStarted;
use App\Events\Report\Composing\Finished as ReportComposingFinished;

class Overview extends Model
{
    use HasFactory, HasStatuses;

    protected $fillable = [
        'administration_id',
        'author_id',
    ];

    protected $appends = [
        'content_title',
        'sent_at',
    ];

    public function getSentAtAttribute($value)
    {   
        $latest_sent_status = $this->latestStatus(['sent']);
        if(!empty($latest_sent_status))
        {
            $time_string = strtotime($latest_sent_status->created_at);
            return date('d-m-Y - H:i', $time_string);
        }
        return null;
    }

    public function getCreatedAtAttribute($value)
    {   
        $time_string = strtotime($value);

        return date('d-m-Y - H:i', $time_string);
    }

    public function administration(): BelongsTo {
        return $this->belongsTo('App\Models\Administration');
    }

    public function reports(): HasMany {
        return $this->hasMany('App\Models\Report');
    }

    public function author(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    public function notifications() {
        return $this->morphMany(Notification::class, 'subject');
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
            ->whereColumn('model_id', 'overviews.id')
            ->where('model_type', 'App\Models\Overview')
            ->orderByDesc('id')
            ->take(1)
        ])->with('lastStatus');
    }

    public function compose()
    {   
        OverviewComposingStarted::dispatch($this);

        foreach($this->reports as $report)
        {   
            ReportComposingStarted::dispatch($report);

            /*
                When supporting other accountancy software create a separate report composer
                and check here if the report will based on a Twinfield administration
                or another accountancy software administration, like Exact online.
            */
            TwinfieldReportComposer::compose($report);

            ReportComposingFinished::dispatch($report);
        }

        OverviewComposingFinished::dispatch($this);
        return $this;
    }

    public function notifyStakeHolders()
    {   
        $this->author->notify(new SendOverviewNotification($this));
    }

    public function getContentTitleAttribute()
    {   

        $report_name = '';

        if(count($this->reports) > 0)
        {
            switch ($this->reports[0]->type) {
                case 'call_posts':
                    $report_name = 'Vraagpostenoverzicht';
                    break;
                case 'debtors':
                    $report_name = 'Debiteurenoverzicht';
                    break;
                default: $this->reports[0]->type;
            }
            
            if(count($this->reports) > 1)
            {
                return $report_name . ' +'. (count($this->reports)-1);
            }
        } 

        return $report_name;
    }

    public function allNotificationsDelivered()
    {
        $notifications = $this->notifications;
        $all_notifications_delivered = true;

        foreach($notifications as $notification)
        {   
            if($notification->status != 'delivered')
            {
                $all_notifications_delivered = false;
            }
        }

        return $all_notifications_delivered;
    }

    public function hasBouncedNotifications()
    {
        $notifications = $this->notifications;
        $has_bounced_notifications = false;

        foreach($notifications as $notification)
        {   
            if($notification->status == 'bounced')
            {
                $has_bounced_notifications = true;
            }
        }

        return $has_bounced_notifications;
    }

    
}
