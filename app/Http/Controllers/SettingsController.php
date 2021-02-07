<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Settings\UpdateInfoRequest;
use App\Models\Account;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;

class SettingsController extends Controller
{
    public function __invoke(): \Inertia\Response
    {   
        return Inertia::render('Settings/Index', [
            'account' => Auth::user()->account,
        ]);
    }

    public function updateInfo(UpdateInfoRequest $request): RedirectResponse
    {   
        $account = Account::find(Auth::user()->account->id);

        $logo = $account->getMedia('logo');
        if(!empty($logo[0]))
        {
            $logo[0]->delete();
        }
        
        $account
            ->addFromMediaLibraryRequest($request->logo)
            ->toMediaCollection('logo');

        return Redirect::route('settings.index')->with('success', __('settings.updated_succesfully'));
    }
}
