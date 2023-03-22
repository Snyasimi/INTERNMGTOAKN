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
        Schema::create('applicants', function (Blueprint $table) {
		$table->id();
		$table->string('Name');
		$table->string('Email')->unique();
		$table->string('PhoneNumber',14);

		$table->string('Position');
        $table->foreign('Position')->references('Position')->on('positions');

		$table->string('url_to_cv_file');
        $table->string('url_to_attachment_letter');
		$table->string('ApplicationStatus');
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
        Schema::dropIfExists('applicants');
    }
};
