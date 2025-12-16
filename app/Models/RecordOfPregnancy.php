<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordOfPregnancy extends Model
{
    protected $table = 'RecordOfPregnancy';
    protected $fillable = ['user_id','preg_week','preg_weight', 'data_to_ulife','deleted_status' ,'weight_status'];
}
