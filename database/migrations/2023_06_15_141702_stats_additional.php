<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('podcasts_episodes', function (Blueprint $table) {
            $table->tinyInteger('announced')->default(0)->after('status');
        });

        Schema::create('radiostations_favorites_history', function(Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('station_id');
            $table->tinyInteger('action');
            $table->timestamp('created_at');

        });
        Schema::create('podcasts_subscriptions_history', function(Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('podcast_id');
            $table->tinyInteger('action');
            $table->timestamp('created_at');
        });

    }

    public function down(): void
    {
        Schema::table('podcasts_episodes', function (Blueprint $table) {
            $table->dropColumn('announced');
        });
        Schema::drop('radiostations_favorites_history');
        Schema::drop('podcasts_subscriptions_history');


    }
};
