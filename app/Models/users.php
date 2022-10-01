<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;


class users extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;    
    protected $table = "users";
    protected $fillable = [
        'Fname',
        'Lname',
        'Phone',
        'Country',
        'City',
        'email',
        'password',
        'Type_User',
    ];
    protected $hidden = [
        'Password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
   
    public function medicine(){
        return $this->belongsToMany(Mdicine::class, 'med_lists','user_id','medicine_id')
        ->withPivot(['dosing_Times','created_at','updated_at']);
    }
    public function pharmacy(){
        return $this->belongsToMany(Pharmacy::class, 'cus_with_phar','user_id','pharmacy_id')
        ->withPivot(['blacklist','created_at','updated_at']);
    }
}
