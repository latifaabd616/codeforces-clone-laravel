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
        Schema::create('user_projects', function (Blueprint $table) {
          
            $table->id();
               
               $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
               $table->foreignId('Project_id')->constrained('Projects')->cascadeOnDelete();
            
               $table->date('StartDate')->useCurrent();
               $table->date('FinishDate')->nullable();

               $table->string('Status', 50)->default('pending');
               $table->string('ReviewStatus', 50)->default('pending');
                $table->date('ReviewDate')->nullable();
               $table->string('Submittedfile')->nullable();
               $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_projects');
    }
};
