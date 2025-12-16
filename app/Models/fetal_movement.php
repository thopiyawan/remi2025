<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fetal_movement extends Model
{
    protected $table = 'fetal_movement';
    protected $fillable = ['user_id','preg_week','date', 'num_morning','num_noon' ,'num_evening'];
}
