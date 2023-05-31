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
        Schema::table('users_history', function (Blueprint $table) {
            $table->boolean('is_listened')->default(false)->after('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_history', function (Blueprint $table) {
            $table->dropColumn('is_listened');
        });
    }
};
