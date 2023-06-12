<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void {
        Schema::table('users', function(Blueprint $table) {
            $table->datetime('last_login_at')->nullable();
            $table->string('last_login_ip', 15)->nullable();
        });

        Schema::table('users_downloads', function (Blueprint $table) {
            $table->tinyInteger('is_deleted')->default(0);
        });

        Schema::create('radiostations_history', function (Blueprint $table){
            $table->integer('station_id');
            $table->integer('user_id');
            $table->string('ip', 15);
            $table->datetime('click_time');
        });

        Schema::create('podcasts_history', function (Blueprint $table){
            $table->integer('episode_id');
            $table->integer('user_id');
            $table->string('ip', 15);
            $table->datetime('click_time');
        });
    }

    public function down(): void {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('last_login_at');
            $table->dropColumn('last_login_ip');
        });

        Schema::table('downloads', function(Blueprint $table) {
            $table->dropColumn('is_deleted');
        });

        Schema::drop('radiostations_history');
        Schema::drop('podcasts_history');
    }
};
