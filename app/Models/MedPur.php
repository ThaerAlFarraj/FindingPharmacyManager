<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedPur extends Model
{
    use HasFactory;
    protected $primarykey = ['purchase_id', 'medicine_id','pharmacy_id'];
    protected $table = "med_purs";

}
