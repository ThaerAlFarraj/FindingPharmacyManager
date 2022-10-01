<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedList extends Model
{
    use HasFactory;
    protected $primarykey = ['user_id', 'medicine_id'];
    protected $table = "med_lists";

}
