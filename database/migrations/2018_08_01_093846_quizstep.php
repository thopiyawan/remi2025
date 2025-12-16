<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Quizstep extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizstep', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id',50);
            $table->string('code_quiz',5);
            $table->string('question_num',3);
            $table->string('question_ans');
            $table->integer('answer_status');
            $table->integer('correct_ans');
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
         Schema::dropIfExists('quizstep');
    }
}
