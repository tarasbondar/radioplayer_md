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
        Schema::table('podcasts_episodes', function (Blueprint $table) {
            $table->addColumn('string', 'filename', ['length' => 255, 'after' => 'source'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('podcasts_episodes', function (Blueprint $table) {
            $table->dropColumn('filename');
        });
    }
};
