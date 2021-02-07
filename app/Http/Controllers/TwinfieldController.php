<?php

namespace App\Http\Controllers;

use App\Helpers\TwinfieldConnector;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TwinfieldController extends Controller
{
    public function validateCredentials()
    {   
        if (TwinfieldConnector::credentialsAreValidForUser(Auth::user()))
        {
            return response()->json([
                'status' => 'success',
            ]);
        }
        return response()->json([
            'status' => 'error',
        ]);
    }
}


