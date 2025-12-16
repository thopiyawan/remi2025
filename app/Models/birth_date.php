<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class birth_date extends Model
{
    protected $table = 'birth_date';
    protected $fillable = ['user_id','birthdate', 'week'];
}
