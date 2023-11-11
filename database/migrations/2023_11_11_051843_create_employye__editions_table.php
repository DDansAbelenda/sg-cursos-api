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
        Schema::create('employye__editions', function (Blueprint $table) {
            $table->bigInteger('edition_id');
            $table->bigInteger('course_id');
            $table->timestamps();

            /*Constraints*/
            $table->foreign('edition_id')->references('id')->on('editions')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unique(['edition_id','course_id']);

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
