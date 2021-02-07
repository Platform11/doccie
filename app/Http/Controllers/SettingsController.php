<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __invoke(): \Inertia\Response
    {   
        return Inertia::render('Settings/Index', [
            'account' => Auth::user()->account,
        ]);
    }
}
