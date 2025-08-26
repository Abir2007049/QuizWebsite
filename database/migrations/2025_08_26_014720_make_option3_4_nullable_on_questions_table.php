<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('option1')->nullable()->change();
            $table->string('option2')->nullable()->change();
            $table->string('option3')->nullable()->change();
            $table->string('option4')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('option1')->nullable(false)->change();
            $table->string('option2')->nullable(false)->change();
            $table->string('option3')->nullable(false)->change();
            $table->string('option4')->nullable(false)->change();
        });
    }
};


