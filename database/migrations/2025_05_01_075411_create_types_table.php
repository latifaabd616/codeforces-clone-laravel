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
        Schema::create('types', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment
                $table->string('Title', 100);
                $table->text('Criteria');
                $table->string('Icon', 255);
                $table->date('GrantDate');
                $table->timestamps(); // Optional: adds created_at & updated_at
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};
