<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\TwinfieldController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => '', 'middleware'=> ['auth']], function() {
  Route::get('')->name('dashboard')->uses(AdministrationController::class);
});

Route::group(['prefix' => 'users', 'middleware'=> ['auth']], function() {
  Route::get('')->name('users.index')->uses(UserController::class);
  Route::get('create')->name('users.create')->uses([UserController::class, 'create']);
  Route::get('{user}')->name('users.show')->uses([UserController::class, 'show']);
  Route::post('')->name('users.store')->uses([UserController::class, 'store']);
  Route::post('update')->name('users.update')->uses([UserController::class, 'update']);
  Route::post('delete/{user}')->name('users.delete')->uses([UserController::class, 'delete']);
});

Route::group(['prefix' => 'invite'], function() {
  Route::get('{token}')->name('invite.show')->uses([InviteController::class, 'show']);
  Route::post('accept')->name('invite.accept')->uses([InviteController::class, 'accept']);
});

Route::group(['prefix' => 'profile', 'middleware'=> ['auth']], function() {
  Route::get('')->name('profile.show')->uses(ProfileController::class);
  Route::post('update/info')->name('profile.update.info')->uses([ProfileController::class, 'update']);
  Route::post('update/password')->name('profile.update.password')->uses([ProfileController::class, 'updatePassword']);
  Route::post('update/twinfield-credentials')->name('profile.update.twinfield-credentials')->uses([ProfileController::class, 'updateTwinfieldCredentials']);
});

Route::group(['prefix' => 'administrations', 'middleware'=> ['auth']], function() {
  Route::get('')->name('administrations.index')->uses(AdministrationController::class);
  Route::get('create')->name('administrations.create')->uses([AdministrationController::class, 'create']);
  Route::post('')->name('administrations.store')->uses([AdministrationController::class, 'store']);
  Route::post('delete/{administration}')->name('administrations.delete')->uses([AdministrationController::class, 'delete']);
  Route::post('update/relation_manager')->name('administrations.update.relation_manager')->uses([AdministrationController::class, 'updateRelationManager']);
  Route::post('update/contact_person')->name('administrations.update.contact_person')->uses([AdministrationController::class, 'updateContactPerson']);
  Route::post('update/info')->name('administrations.update.info')->uses([AdministrationController::class, 'updateInfo']);
  Route::get('{administration}')->name('administrations.show')->uses([AdministrationController::class, 'show']);
  Route::get('send-report/{administration}')->name('administrations.send-report')->uses([AdministrationController::class, 'sendReport']);
  Route::get('info/{administration}')->name('administrations.info')->uses([AdministrationController::class, 'getInfo']);
});

Route::group(['prefix' => 'settings', 'middleware'=> ['auth']], function() {
  Route::get('')->name('settings.index')->uses(SettingsController::class);
  Route::post('update/info')->name('settings.update.info')->uses([SettingsController::class, 'updateInfo']);
});

Route::group(['prefix' => 'twinfield'], function() {
  Route::get('validate-credentials')->name('twinfield.validate-credentials')->uses([TwinfieldController::class, 'validateCredentials']);
});

Route::group(['prefix' => 'pdf'], function() {
  Route::get('vraagposten')->name('pdf.vraagposten')->uses([PdfController::class, 'vraagposten']);
});