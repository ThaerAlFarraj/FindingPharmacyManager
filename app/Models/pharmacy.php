<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class pharmacy extends Authenticatable implements JWTSubject
{ use HasApiTokens, HasFactory, Notifiable;

    protected $table = "pharmacies";
    protected $fillable = [
        'name',
        'email',
        'password',
        'shift_days',
        'workhours',
        'address_longitude',
        'address_latitude',
        'phone'

    ];
        public function medicine(){
        return $this->belongsToMany(Medicine::class, 'med__in__phares','medicines_id','pharmacies_id')
        ->withPivot(['quan_In_Phar','quan_In_Don','created_at','updated_at']);
    }
    public function user(){
        return $this->belongsToMany(users::class, 'cus_with_phar','user_id','pharmacy_id')
        ->withPivot(['blacklist','created_at','updated_at']);
    }
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
   

}
