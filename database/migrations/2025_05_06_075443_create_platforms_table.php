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
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();  // Primary Key (Auto-increment)
            $table->string('Title', 100);
            $table->string('Icon', 255)->nullable(); // nullable() إذا كان الحقل غير مطلوب
            $table->timestamps(); // اختياري: لحقول created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platforms');
    }
};
