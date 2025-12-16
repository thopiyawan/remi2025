<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Doctor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor', function (Blueprint $table) {
            $table->increments('id');
            $table->string('doctor_id');
            $table->string('name');
            $table->string('lastname');
            $table->text('qr_code');
            $table->text('img_profile');
            $table->string('hospital');
            $table->string('password');
            $table->string('type_user');
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
        Schema::dropIfExists('doctor');
    }
}
