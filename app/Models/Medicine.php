<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $table = "medicines";

    public function pharmacy(){

        return $this->belongsToMany(pharmacy::class, 'med__in__phares','medicines_id','pharmacies_id')
        ->withPivot(['quan_In_Phar','quan_In_Don','created_at','updated_at']);
    }
    public function user(){

        return $this->belongsToMany(users::class, 'med_lists','user_id','medicine_id')
        ->withPivot(['dosing_Times','created_at','updated_at']);
    }

}
