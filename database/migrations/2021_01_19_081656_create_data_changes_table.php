<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_changes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('password_id');
            $table->integer('function_id');
            $table->string('previous_web_address');
            $table->string('previous_description');
            $table->string('previous_login');
            $table->string('previous_password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_changes');
    }
}
