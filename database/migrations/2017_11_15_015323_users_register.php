<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::dropIfExists('users_register');
        Schema::create('users_register', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_id');
            $table->string('user_name',50);
            $table->string('user_age',10);
            $table->string('user_height',6);
            $table->string('user_Pre_weight',6);
            $table->string('user_weight',6);
            $table->string('preg_week',10);
            $table->string('phone_number',10);
            $table->string('email',50);
            $table->string('hospital_name',50);
            $table->string('hospital_num',12);
            $table->string('history_medicine',30);
            $table->string('history_food',30);
            $table->tinyInteger('active_lifestyle')->unsigned();
            $table->tinyInteger('status')->unsigned();
            $table->string('date_preg',10);
            // $table->timestamp('created_at')->nullable();
            // $table->timestamp('updated_at')->nullable();
            $table->string('dateofbirth',10);
            $table->string('ulife_connect');
            $table->timestamps();
            $table->softDeletes()->nullable();
            $table->tinyInteger('compli_diabete')->unsigned();
            $table->tinyInteger('compli_hypertension')->unsigned();
            $table->tinyInteger('compli_preterm_birth')->unsigned();
            $table->tinyInteger('weight_status')->unsigned();
            $table->string('preg_week_str');
            $table->tinyInteger('type_preg_week')->unsigned();
            $table->string('due_date');
            //$table->timestamp('updated_at');
            //$table->rememberToken(); 
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_register');
    }
}
