<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Overview;
use App\Events\Notification\Delivered as NotificationDelivered;
use App\Events\Notification\Bounced as NotificationBounced;

class ProcessMailFeedback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request_content)
    {
        $this->request = json_decode($request_content);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = $this->getModel();

        if(empty($model))
        {   
            //unsupported model
            $this->fail();
        }

        $model_notification = $model->notifications()->where('recipient', $this->request->Recipient)->first();

        if(empty($model_notification))
        {   
            $this->fail();
        }
          
        if($this->request->RecordType == 'Delivery')
        {
          NotificationDelivered::dispatch($model_notification, json_encode($this->request));
        }

        if($this->request->RecordType == 'HardBounce')
        {
          NotificationBounced::dispatch($model_notification, json_encode($this->request));
        }
    }

    private function getModel() {
        if($this->request->Metadata->model == 'Overview')
        {   
            return Overview::find((int) $this->request->Metadata->model_id);
        }
    }

}
