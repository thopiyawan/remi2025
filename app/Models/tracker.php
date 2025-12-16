<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tracker extends Model
{
     protected $table = 'tracker';
     protected $fillable = ['user_id','date','breakfast', 'lunch','dinner', 'dessert_lu' ,'dessert_din','exercise','vitamin', 'time_breakfast','time_lunch' ,'time_dinner'];

}

