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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userproject_id')->constrained('user_projects')->cascadeOnDelete();
            $table->foreignId('Platform_id')->nullable()->constrained('platforms')->cascadeOnDelete();
            $table->foreignId('framework_id')->nullable()->constrained('frameworks')->cascadeOnDelete();
            $table->foreignId('programminglanguage_id')->nullable()->constrained('programming_languages')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
