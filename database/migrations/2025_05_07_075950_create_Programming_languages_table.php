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
        Schema::create('Programming_languages', function (Blueprint $table) {
            $table->id(); // مفتاح أساسي مع auto-increment
            

                $table->string('Title', 100);
                $table->string('Icon', 255)->nullable();
                $table->timestamps(); // حقول created_at و updated_at (اختياري)
            
         });}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Progrmming_languages');
    }
};
