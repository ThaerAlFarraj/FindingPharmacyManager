<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Med_In_Phare extends Model
{

    protected $primarykey = ['medicines_id', 'pharmacies_id'];
    use HasFactory;
    protected $table = 'med__in__phares';
    // public function cusWithPhar(){
    //     return $this->belongsToMany(cusWithPhar::class, 'donated_medicines','user_id','pharmacy_id','medicine_id')
    //     ->withPivot(['status','quantity','created_at','updated_at']);
    // }
}
