<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\Admin as Authenticatable;

class Admin extends Model
{
    use HasApiTokens,HasFactory,Notifiable;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
