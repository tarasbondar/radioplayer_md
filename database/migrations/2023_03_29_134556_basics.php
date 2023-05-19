<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function(Blueprint $table) {
            $table->tinyInteger('role')->default(0)->after('email');
            $table->tinyInteger('status')->default(0)->after('role');
            $table->string('language', 2)->after('status');

        });

        Schema::create('author_applications', function(Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('title');
            $table->string('description');
            $table->string('categories');
            $table->string('tags')->nullable();
            $table->string('image');
            $table->string('example');
            $table->tinyInteger('status')->default(1);
            $table->string('feedback_message')->nullable();
            $table->timestamps();
        });

        Schema::create('radiostations', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('source');
            $table->string('source_hd')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('image_logo');
            $table->timestamps();
        });

        Schema::create('radiostations_categories', function(Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('radiostations_2_categories', function(Blueprint $table) {
            $table->id();
            $table->bigInteger('station_id');
            $table->bigInteger('category_id');
            $table->dateTime('created_at');
        });

        Schema::create('radiostations_tags', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::create('radiostations_2_tags', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('station_id');
            $table->bigInteger('tag_id');
            $table->dateTime('created_at');
        });

        Schema::create('radiostations_favorites', function(Blueprint $table) {
            $table->bigInteger('user_id');
            $table->bigInteger('station_id');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::create('podcasts', function(Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->bigInteger('owner_id');
            $table->string('tags');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('podcasts_categories', function(Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('podcasts_2_categories', function(Blueprint $table) {
            $table->id();
            $table->bigInteger('podcast_id');
            $table->bigInteger('category_id');
            $table->dateTime('created_at')->default(now());
        });

        Schema::create('podcasts_episodes', function (Blueprint $table){
            $table->id();
            $table->bigInteger('podcast_id');
            $table->string('name');
            $table->string('description');
            $table->string('tags')->nullable();
            $table->string('source');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

    }


    public function down(): void
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('status');
            $table->dropColumn('language');
        });
        Schema::drop('author_applications');
        Schema::drop('radiostations');
        Schema::drop('radiostations_favorites');
        Schema::drop('radiostations_categories');
        Schema::drop('radiostations_2_categories');
        Schema::drop('radiostations_tags');
        Schema::drop('radiostations_2_tags');
        Schema::drop('podcasts');
        Schema::drop('podcasts_categories');
        Schema::drop('podcasts_2_categories');
        Schema::drop('podcasts_episodes');
    }
};
