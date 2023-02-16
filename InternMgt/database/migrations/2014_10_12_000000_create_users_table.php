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
        Schema::create('users', function (Blueprint $table) {

            $table->ulid('user_id')->primary();
            $table->string('Name');
	    $table->string('Email')->unique();
	    $table->integer('PhoneNumber');
        //DROP
	    $table->bigInteger('department_id');
	    $table->foreign('department_id')->references('id')->on('departments');
        
        $table->string('Position');
        $table->foreign('Position')->references('id')->on('positions');
	    
        $table->bigInteger('Role');
        $table->foreign('Role')->references('id')->on('roles');

	    $table->string('Supervisor')->nullable();
	    $table->foreign('Supervisor')->references('user_id')->on('users');

	    $table->boolean('Status');
            
	    $table->timestamp('email_verified_at')->nullable();
        $table->string('password')->nullable();
        $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};