<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Report;
use App\Models\Notification;

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
            // If there is no notification for the given recipient investigation is required.
            $this->fail();
        }
          
        if($this->request->RecordType == 'Delivery')
        {
          $model_notification->delivery_status = 'delivered';
        }

        if($this->request->RecordType == 'HardBounce')
        {
          $model_notification->delivery_status = 'bounced';
        }

        $model_notification->delivery_meta = json_encode($this->request);

        $model_notification->save();
    }

    private function getModel() {
        if($this->request->Metadata->model == 'Report')
        {   
            return Report::find((int) $this->request->Metadata->model_id);
        }
    }

}
