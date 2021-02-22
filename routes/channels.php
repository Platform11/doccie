<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Account;
use App\Models\Administration;
use App\Models\Overview;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('account.{accountId}', function ($user, $accountId) {
    return $user->account->id === (int) $accountId;
});

Broadcast::channel('administration.{administrationId}', function ($user, $administrationId) {
    return $user->account->id === Administration::find((int) $administrationId)->account->id;
});

Broadcast::channel('overview.{overviewId}', function ($user, $overviewId) {
    return $user->account->id === Overview::find((int) $overviewId)->administration->account->id;
});


// Broadcast::channel('overview.{overviewId}', function ($user, $overviewId) {
//     return $user->account->id === Overview::find($overviewId)->administration->account->id;
// });

// Broadcast::channel('{accountId}.notifications', function ($user, $accountId) {
//     return $user->account->id === $accountId;
// });