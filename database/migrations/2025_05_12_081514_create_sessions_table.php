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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
           
             $table->foreignId('UserProject_id')->constrained('user_projects')->cascadeOnDelete();
             
             
             $table->dateTime('StartTime')->useCurrent();
             $table->dateTime('EndTime')->nullable();
             
            
             $table->integer('SessionTime')->default(0)->comment('المدة الكلية للجلسة بالثواني');
             $table->integer('ActiveTime')->default(0)->comment('زمن الترميز الفعلي بالثواني');
             $table->integer('EditedLines')->default(0);
             $table->integer('SuccessfulRuns')->default(0);
             $table->integer('ErrorRuns')->default(0);
             $table->integer('MeanTimeToFixError')->default(0)->comment('متوسط وقت إصلاح الأخطاء بالثواني');
             $table->integer('CodingPeriod')->default(0)->comment('فترة الترميز المستمرة بالثواني');
             
            
             $table->timestamps();
             
    }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
