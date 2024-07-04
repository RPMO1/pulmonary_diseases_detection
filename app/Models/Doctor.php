<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor extends Authenticatable
{
    use HasApiTokens,HasFactory ,Notifiable;
    protected $fillable = [
        'name',
        'phone',
        'address',
        'years_of_experience',
        'email',
        'password'
    ];
    public function appointments(){
        return $this->hasMany(Appointment::class);
    }
    public function patient(){
        return $this->hasMany(Patient::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
