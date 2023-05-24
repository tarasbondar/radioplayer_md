<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void {
        Schema::table('radiostations', function (Blueprint $table) {
            $table->string('source_meta')->after('source_hd')->nullable();
            $table->tinyInteger('group_id')->after('description');
            $table->string('description')->nullable()->change();
        });

        Schema::create('radiostations_groups', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::drop('radiostations_groups');
        Schema::table('radiostations', function(Blueprint $table) {
            $table->dropColumn('source_meta');
            $table->dropColumn('group_id');
            $table->string('description')->change();
        });
    }
};
