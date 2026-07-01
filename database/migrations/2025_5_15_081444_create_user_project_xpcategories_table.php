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
        Schema::create('user_project_xpcategories', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('UserProjectID');
            //$table->unsignedBigInteger('CategoryID');
            $table->integer('XPValue');
            $table->text('Notice')->nullable();
            
            // Foreign keys
            
                  $table->foreignId('UserProject_id')->constrained('User_projects')->cascadeOnDelete();
                  $table->foreignId('Xpcategory_id')->constrained('Xpcategories')->cascadeOnDelete();
               
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_project_xpcategories');
    }
};
