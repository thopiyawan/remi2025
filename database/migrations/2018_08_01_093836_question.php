<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Question extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question', function (Blueprint $table) {
            $table->increments('id');
            $table->string('question_num',3);
            $table->string('code_quiz',5);
            $table->text('question');
            $table->text('answer');
            $table->text('choice1');
            $table->text('choice2');
            $table->text('choice3');
            $table->text('content_sugg');
            $table->date('date_question');
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
         Schema::dropIfExists('question');
    }
}
