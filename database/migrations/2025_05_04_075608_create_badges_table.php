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
        Schema::create('badges', function (Blueprint $table) {
            $table->id(); // سيتم إنشاء حقل تلقائي كمفتاح أساسي
            $table->string('Title', 100);
            $table->text('Criteria');
            $table->string('Icon', 255);
            $table->timestamps();
    });}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
