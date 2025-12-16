<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class blood_sugar extends Model
{
    protected $table = 'blood_sugar';
    protected $fillable = ['user_id','meal', 'time_of_day','datetime' ,'blood_sugar','preg_week'];
    

}
