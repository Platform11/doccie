<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\DeleteRequest;
use App\Http\Requests\User\ShowRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Jobs\InviteUser;

class UserController extends Controller
{
    public function __invoke(): \Inertia\Response
    {
        $account_id = Auth::user()->account->id;
        $users = User::where('account_id', $account_id)->get();

        return Inertia::render('Users/Index', ['users' => $users]);
    }

    public function show(ShowRequest $request, User $user): \Inertia\Response
    {   
        return Inertia::render('Users/Show', [
            'user' => $user->makeVisible(['twinfield_username', 'twinfield_office']),
            'administrations' => $user->administrations()->with('relation_manager')->get(),
        ]);
    }

    public function create(): \Inertia\Response
    {   
        return Inertia::render('Users/Create');
    }

    public function store(StoreRequest $request): RedirectResponse
    {   
        $validated = $request->validated();

        $user = new User;
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->account_id = Auth::user()->account_id;
        $user->status = 'invited';
        $user->password = Hash::make(Str::random(20));
        $user->save();

        InviteUser::dispatch(Auth::user(), $user);

        return Redirect::route('users.index')->with('success', __('users.added_sucessfully', ['name' => $user->first_name]));
    }

    public function update(UpdateRequest $request): RedirectResponse
    {   
        $validated = $request->validated();

        $user = User::find($validated['id']);
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->save();

        return Redirect::route('users.show', ['user' => $validated['id']])->with('success', __('users.updated_succesfully'));
    }

    public function delete(DeleteRequest $request, User $user): RedirectResponse
    {   
        $administrations = $user->administrations;

        if(!empty($administrations))
        {
            foreach($administrations as $administration)
            {
                $administration->relation_manager_id = Auth::user()->id;
                $administration->save();
            }
        }

        if(!empty($user->invite))
        {   
            $user->invite->delete();
        }
        
        $user->delete();

        return Redirect::route('users.index')->with('success', __('users.deleted_succesfully', ['name' =>  $user->first_name]));
    }
}
