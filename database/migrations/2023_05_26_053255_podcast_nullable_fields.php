<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('podcasts', function (Blueprint $table){
            $table->string('tags')->nullable()->change();
            $table->string('image')->nullable()->change();
        });
        Schema::table('author_applications', function (Blueprint $table) {
            $table->string('description', 4096)->nullable()->change();
            $table->string('tags', 1024)->nullable()->change();
            $table->string('feedback_message', 4096)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('podcasts', function (Blueprint $table){
            $table->string('tags')->change();
            $table->string('image')->change();
        });

        Schema::table('author_applications', function (Blueprint $table) {
            $table->string('description', 255)->nullable()->change();
            $table->string('tags', 255)->nullable()->change();
            $table->string('feedback_message', 255)->nullable()->change();
        });
    }
};
