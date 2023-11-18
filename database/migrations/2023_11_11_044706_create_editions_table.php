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
        Schema::create('editions', function (Blueprint $table) {
            $table->id();
            $table->string('code_id');
            $table->bigInteger('course_id');
            $table->bigInteger('employee_id');
            $table->string('place');
            $table->enum('session_period',['Tiempo Completo','Mañana','Tarde']); // fulltime(F), morning(M) and afternon(A)
            $table->date('date');
            $table->timestamps();
   
            /*Constraints*/
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->unique(['code_id','course_id']);
            $table->unique(['course_id', 'date']);

     });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('editions');
    }
};
