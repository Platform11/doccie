<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Contracts\Encryption\DecryptException;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\ModelStatus\HasStatuses;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity, HasRoles, HasStatuses, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'twinfield_username',
        'twinfield_password',
        'twinfield_office',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'twinfield_password',
        'twinfield_username',
        'twinfield_office_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getTwinfieldPasswordAttribute($value)
    {
        try {
            $decrypted = Crypt::decryptString($value);
        } catch (DecryptException $e) {
            return null;
        }
        return $decrypted;
    }

    public function getTwinfieldOfficeCodeAttribute()
    {
        return $this->account()->first()->twinfield_office_code;
    }

    public function setTwinfieldPasswordAttribute($value)
    {
        $this->attributes['twinfield_password'] = Crypt::encryptString($value);
    }

    public function account(): BelongsTo {
        return $this->belongsTo('App\Models\Account');
    }

    public function administrations(): HasMany {
        return $this->hasMany('App\Models\Administration', 'relation_manager_id');
    }

    public function colleagues() {
        return $this->account()->first()->users();
    }

    public function invite(): HasOne {
        return $this->hasOne('App\Models\Invite');
    }

    public function last_status()
    {
        return $this->morphOne('Spatie\ModelStatus\Status', 'model')->orderByDesc('id');
    }
}
