<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStatus\HasStatuses;

class Invite extends Model
{
    use HasFactory, HasStatuses;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
