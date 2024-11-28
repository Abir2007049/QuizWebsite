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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id(); // Primary key for quizzes table
            $table->foreignId('userid')->constrained('users')->onDelete('cascade'); // Foreign key referencing the users table
            $table->string('quizcode')->unique(); // Unique quiz code
            $table->integer('duration'); // Duration of the quiz in minutes
            $table->string('option1'); // Option 1
            $table->string('option2'); // Option 2
            $table->string('option3'); // Option 3
            $table->string('option4'); // Option 4
            $table->string('right_option'); // Correct answer
            $table->timestamps(); // Created at and updated at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
