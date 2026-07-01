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
        Schema::table('sessions', function (Blueprint $table) {
               
            $table->string('CodingPeriod')->default('0')->comment('فترة الترميز المستمرة بالثواني')->change();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
                   $table->integer('CodingPeriod')->default(0)->comment('فترة الترميز المستمرة بالثواني')->change();
        });
    }
};
