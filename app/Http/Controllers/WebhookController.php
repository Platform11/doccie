<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessMailFeedback;
use App\Models\Administration;

class WebhookController extends Controller
{
    public function mail(Request $request)
    {   
        ProcessMailFeedback::dispatch($request->getContent());
    }
}
