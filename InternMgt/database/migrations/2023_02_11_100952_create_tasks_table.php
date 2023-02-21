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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('AssignedBy')->references('user_id')->on('users');
            $table->foreignUlid('AssignedTo')->references('user_id')->on('users');
            $table->string('Task');
            $table->string('Description');
            $table->date('Deadline');
            $table->boolean("Status");

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
        Schema::dropIfExists('tasks');
    }
};
