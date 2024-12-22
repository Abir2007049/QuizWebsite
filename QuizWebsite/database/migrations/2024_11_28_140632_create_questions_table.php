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
        Schema::create('questions', function (Blueprint $table) {
            $table->id(); // Primary key for questions table
            $table->string('text'); 
            $table->string('option1'); // Option 1
            $table->string('option2'); // Option 2
            $table->string('option3'); // Option 3
            $table->string('option4'); // Option 4
            $table->string('right_option'); // Correct answer
            $table->unsignedBigInteger('quiz_id'); // Foreign key referencing the quizzes table
            $table->integer('duration')->nullable(); // Make duration nullable
            $table->timestamps(); // Created at and updated at columns

            // Define foreign key constraint for quiz_id
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
