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
        Schema::table('projects', function (Blueprint $table) {
        $table->json('title_translations')->nullable()->after('Title');
        $table->json('short_description_translations')->nullable()->after('ShortDescription');
        $table->json('long_description_translations')->nullable()->after('LongDescription');
        $table->json('difficulty_translations')->nullable()->after('Difficulty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
               $table->dropColumn([
            'title_translations',
            'short_description_translations',
            'long_description_translations',
            'difficulty_translations'
        ]);
        });
    }
};
