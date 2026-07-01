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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
           // $table->foreignId('Platform_id')->constrained('platforms')->cascadeOnDelete();delete
            $table->string('Title', 100);
             $table->text('ShortDescription');
             $table->text('LongDescription');
             $table->integer('TimeLimit')->comment('الزمن المحدد بالمشروع بالدقائق');
             $table->string('Difficulty', 50);
             $table->integer('XPReward');
            $table->timestamps();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {  
      
        Schema::dropIfExists('projects');
    }
};
