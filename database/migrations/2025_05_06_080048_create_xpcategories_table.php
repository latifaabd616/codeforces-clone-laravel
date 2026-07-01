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
        Schema::create('xpcategories', function (Blueprint $table) {
            $table->id();
            $table->string('Title', 100);
            $table->string('Icon', 255);
            $table->timestamps(); // إضافة حقلي created_at و updated_at تلقائياً
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xpcategories');
    }
};
