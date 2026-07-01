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
        Schema::create('notifications', function (Blueprint $table) {
                       // الطريقة الموصى بها في Laravel 9
                       $table->id(); // Auto-incrementing primary key
            
                       // المفتاح الأجنبي
                       $table->foreignId('user_id')->constrained(
                            'users'// اسم جدول المستخدمين في Laravel
                       
                       )->cascadeOnDelete();
                       
                       $table->string('Title', 255);
                       $table->text('Description');
                       $table->date('ReceiveDate');
                       
                       // حقول التوقيت التلقائية
                       $table->timestamps();
                       
                    
                 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
