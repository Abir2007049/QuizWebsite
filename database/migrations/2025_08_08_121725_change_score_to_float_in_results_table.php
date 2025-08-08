<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->float('score', 5, 2)->change(); // 5 digits total, 2 after decimal
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->integer('score')->change(); // rollback to integer if needed
        });
    }
};
