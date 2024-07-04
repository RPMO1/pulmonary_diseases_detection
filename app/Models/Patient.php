<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Patient extends Model
{
    use HasApiTokens, Notifiable, HasFactory;
    protected $fillable = [
        'fullName',
        'phone',
        'address',
        'age',
        'gender',
        'email',
        'password',
        'doc_id',
    ];
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function audios()
    {
        return $this->hasMany(Result::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
