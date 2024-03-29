<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Inertia\Inertia;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(function ($request) {
            return Inertia::render('Auth/Login')->toResponse($request);
        });

        // Fortify::registerView(function ($request) {
        //     return \Inertia\Inertia::render('Auth/Register')->toResponse($request);
        // });

        Fortify::requestPasswordResetLinkView(function ($request) {
            return Inertia::render('Auth/ForgotPassword')->toResponse($request);
        });

        Fortify::resetPasswordView(function ($request) {
            return Inertia::render('Auth/ResetPassword', ['token' => $request->token])->toResponse($request);
        });

        Fortify::verifyEmailView(function ($request) {
            return Inertia::render('Auth/VerifyEmail')->toResponse($request);
        });

        Fortify::twoFactorChallengeView(function($request) {
            return Inertia::render('Auth/TwoFactor')->toResponse($request);
        });
    }
}
