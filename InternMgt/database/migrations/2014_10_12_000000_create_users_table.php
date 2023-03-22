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
	    Schema::disableForeignKeyConstraints();
	    Schema::create('users', function (Blueprint $table) {

		$table->ulid('user_id')->primary();
		$table->index('user_id');
	
		$table->string('Name');
		$table->string('Email')->unique();	
		$table->string('PhoneNumber',14);

		
		$table->string('Position')->nullable();
		$table->foreign('Position')->references('Position')->on('positions')->onDelete('set null');

		
		$table->string('Role')->nullable();
		$table->foreign('Role')->references('Role')->on('roles')->onDelete('set null');

		$table->string('Supervisor')->nullable();
		$table->foreign('Supervisor')->references('user_id')->on('users')->onDelete('set null');

		$table->boolean('Status');

		
		$table->string('password')->nullable();
        //uncomment the code beow to add remember me functionality
		//$table->rememberToken();
		$table->timestamps();
	
	});
	
	    Schema::enableForeignKeyConstraints();
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

    public function before()
    {
        $this->before =   "2023_02_15_161820_create_positions_table.php";
        $this->before =   "2023_02_15_165514_create_roles_table.php";
    }
};
