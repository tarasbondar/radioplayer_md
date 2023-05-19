<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('podcasts_subscriptions', function (Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('podcast_id');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('users_queues', function(Blueprint $table) { //listen later
            $table->bigInteger('user_id');
            $table->bigInteger('episode_id');
            $table->integer('order')->default(1);
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('users_history', function(Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('episode_id');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('users_downloads', function (Blueprint $table){
            $table->bigInteger('user_id');
            $table->bigInteger('episode_id');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void
    {
        Schema::drop('podcasts_subscriptions');
        Schema::drop('users_queues');
        Schema::drop('users_history');
        Schema::drop('users_downloads');
    }
};
