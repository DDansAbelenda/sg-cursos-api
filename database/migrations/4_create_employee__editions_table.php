<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee__editions', function (Blueprint $table) {
            $table->unsignedBigInteger('edition_id');
            $table->unsignedBigInteger('employee_id');

            /*Constraints*/
            $table->foreign('edition_id')->references('id')->on('editions')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->primary(['edition_id','employee_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employye__editions');
    }
};
