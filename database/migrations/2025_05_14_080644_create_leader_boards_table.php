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
        Schema::create('leader_boards', function (Blueprint $table) {
           // الطريقة الموصى بها في Laravel 9
           $table->id(); // مفتاح أساسي تلقائي
            
           // المفتاح الأجنبي
           $table->foreignId('user_id')->constrained('users' )->cascadeOnDelete();
           $table->integer('XP')->default(0);
           $table->integer('Rank');
           $table->date('Date');
           
           // إضافة timestamps إذا كنت بحاجة إليها
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leader_boards');
    }
};
