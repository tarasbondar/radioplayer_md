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
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('episode_id');
            $table->integer('sort')->default(0)->nullable();
            // Add any additional columns you require

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('episode_id')->references('id')->on('podcasts_episodes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
