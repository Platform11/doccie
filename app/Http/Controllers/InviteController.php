<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Invite;
use App\Http\Requests\Invites\AcceptRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class InviteController extends Controller
{
    public function show(Request $request): \Inertia\Response
    {   
        $invite = Invite::where('token', $request->token)->first();

        if(empty($invite) || !empty($invite->accepted))
        {   
            $reason = 'unknown';

            if(empty($invite))
            {
                $reason = 'not valid';
            }

            if(!empty($invite->accepted))
            {
                $reason = 'already accepted';
            }

            return Inertia::render('Invites/NotValid', ['reason' => $reason]);
        }
        return Inertia::render('Invites/Accept', ['token'=>$request->token]);
    }

    public function accept(AcceptRequest $request): RedirectResponse
    {   
        $validated = $request->validated();
        $invite = Invite::where('token', $validated['token'])->first();
        $invite->accepted = now();
        $invite->save();

        $user = $invite->user;
        $user->password = Hash::make($validated['password']);
        $user->status = 'active';
        $user->save();        

        return Redirect::route('login')->with('success', __('users.password_setup_succesfully'));
    }
}
