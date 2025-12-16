<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RewardGift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('reward_gift', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code_gift',5);
            $table->string('name_gift');
            $table->string('images_gift');
            $table->string('point',3);
            $table->date('date_start')->nullable();
            $table->date('date_exp')->nullable();
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
         Schema::dropIfExists('reward_gift');
    }
}
