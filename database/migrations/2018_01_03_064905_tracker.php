<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tracker extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tracker', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->string('breakfast');
            $table->string('lunch');
            $table->string('dinner');
            $table->string('dessert_lu');
            $table->string('dessert_din');
            $table->string('exercise');
            $table->string('vitamin');
            $table->timestamps();
            $table->string('data_to_ulife');
            // $table->timestamp('created_at')->nullable();
            // $table->timestamp('updated_at')->nullable();
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
           Schema::dropIfExists('tracker');
    }
}
