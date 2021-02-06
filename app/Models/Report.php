<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Notification;

class Report extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $appends = [
        'sent_to',
        'sent_status',
    ];

    public function getCreatedAtAttribute($value)
    {   
        $time_string = strtotime($value);

        return date('d-m-Y - H:i', $time_string);
    }

    public function getSentToAttribute()
    {   
        if(count($this->notifications) > 0)
        {
            return $this->notifications->first()->recipient;
        }
        return;
        

        // if(count($notifications) > 1)
        // {   
        //     return $notifications->first()->recipient. ' + '. (count($notifications) - 1);
        // }
    }

    public function getSentStatusAttribute()
    {   
        $notifications = $this->notifications;

        $received_by = 0;
        $bounced = 0;

        foreach($notifications as $notification)
        {
            if($notification->delivery_status == 'bounced')
            {
                $bounced++;
            }

            if($notification->delivery_status == 'delivered')
            {
                $received_by++;
            }
        }

        $status = 'in_transit';

        if($received_by == count($notifications))
        {
            $status = 'delivered';
        }

        if($bounced > 0)
        {
            $status = 'failed';
        }

        return $status;
    }

    public function author(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    public function notifications() {
        return $this->morphMany(Notification::class, 'subject');
    }

}
