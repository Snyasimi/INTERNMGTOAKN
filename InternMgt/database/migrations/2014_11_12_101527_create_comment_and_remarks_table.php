<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_and_remarks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('task_id');

            $table->foreign('task_id')->references('id')->on('tasks');

            $table->string("Comments");

            //$table->bigInteger('user_id');
            $table->foreignUlid('user_id')->references('user_id')->on('users');

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
        Schema::dropIfExists('comment_and_remarks');
    }
};
