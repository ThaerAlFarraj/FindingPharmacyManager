<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cusWithPhar extends Model
{
    use HasFactory;
    protected $table = "cus_with_phar";
    protected $primarykey = ['user_id', 'pharmacy_id'];
    // public function medInPhar(){
    //     return $this->belongsToMany(Med_In_Phare::class, 'donated_medicines','user_id','pharmacy_id','medicine_id')
    //     ->withPivot(['status','quantity','created_at','updated_at']);
    // }
}
