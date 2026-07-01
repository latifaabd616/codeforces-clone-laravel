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
        Schema::create('project_technologies', function (Blueprint $table) {
            $table->id();
             $table->foreignId('project_id')->nullable()->constrained('Projects')->cascadeOnDelete();
             $table->foreignId('ProgrammingLanguage_id')->nullable()->constrained('Programming_languages')->cascadeOnDelete();
             $table->foreignId('framework_id')->nullable()->constrained('frameworks')->cascadeOnDelete();
             $table->foreignId('platform_id')->nullable()->constrained('platforms')->cascadeOnDelete();
             
             
             $table->integer('ExtraXP')->default(0);
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_technologies');
    }
};
