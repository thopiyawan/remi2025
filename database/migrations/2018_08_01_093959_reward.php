<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reward extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id',50);
            $table->string('code_quiz',5);
            $table->string('point',3);
            $table->string('feq_ans_meals',3);
            $table->string('feq_ans_week',3);
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
         Schema::dropIfExists('reward');
    }
}
