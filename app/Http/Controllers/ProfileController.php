<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateTwinfieldCredentialsRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class ProfileController extends Controller
{
    public function __invoke(): \Inertia\Response
    {   
        return Inertia::render('Profile/Show', [
            'twinfield_credentials' => [Auth::user()->twinfield_username, Auth::user()->account()->first()->twinfield_office_code],
            'administrations' => Auth::user()->administrations()->with(['relation_manager'])->get(),
        ]);
    }


    public function update(UpdateRequest $request): RedirectResponse
    {   
        $validated = $request->validated();

        $user = User::find($validated['id']);
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->save();

        return Redirect::route('profile.show')->with('success', __('profile.updated_succesfully'));
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {   
        $validated = $request->validated();

        $user = User::find($validated['id']);
        $user->password = Hash::make($validated['password']);
        $user->save();

        return Redirect::route('profile.show')->with('success', __('profile.updated_password_succesfully'));
    }

    public function updateTwinfieldCredentials(UpdateTwinfieldCredentialsRequest $request): RedirectResponse
    {   
        $validated = $request->validated();

        $user = Auth::user();
        $user->twinfield_username = $validated['twinfield_username'];
        $user->twinfield_password = $validated['twinfield_password'];
        $user->save();

        return Redirect::route('profile.show')->with('success', __('profile.updated_twinfield_credentials_succesfully'));
    }
}
