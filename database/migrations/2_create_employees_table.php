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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name',55);
            $table->string('last_names',55);
            $table->string('address');
            $table->string('phone');
            $table->char('nif',10);
            $table->date('date_birth');
            $table->string('nationality');
            $table->double('salary');
            $table->enum('sex', ['Masculino','Femenino']);
            $table->boolean('is_qualified');
            $table->timestamps();
            
            //Constrains
            $table->unique('nif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
