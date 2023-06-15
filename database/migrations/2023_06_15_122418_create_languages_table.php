<?php

use App\Models\Language;
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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->integer('sort')->default(0)->nullable();
            $table->boolean('is_main')->default(false);
            $table->boolean('published')->default(true);
            $table->timestamps();
        });

        Language::create([
            'code' => 'ru',
            'name' => 'Russian',
            'sort' => 1,
            'is_main' => true,
        ]);

        Language::create([
            'code' => 'en',
            'name' => 'English',
            'sort' => 2,
            'is_main' => false,
        ]);

        Language::create([
            'code' => 'ro',
            'name' => 'Romanian',
            'sort' => 3,
            'is_main' => false,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
