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
		$table->index('id');
            $table->foreignUlid('AssignedBy')->nullable()->references('user_id')->on('users')->onDelete('set null');
            $table->foreignUlid('AssignedTo')->nullable()->references('user_id')->on('users')->onDelete('set null');
            $table->string('Task');
            $table->string('Description');
            $table->date('Deadline');
	    $table->string("Status");
	    $table->integer('Rating')->nullable();

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
