<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PersonalDoctorMom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_doctor_mom', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('doctor_id');
            $table->timestamps();
            $table->softDeletes()->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_doctor_mom');
    }
}
