<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tracker_activity extends Model
{
    // use HasFactory;
    protected $table = 'tracker_activity';
    protected $fillable = ['user_id','food_id','date','time', 'meal','food_name', 'portion' ,'calorie','unit','exercise', 'vitamin','created_at' ,'updated_at','deleted_at'];

}
