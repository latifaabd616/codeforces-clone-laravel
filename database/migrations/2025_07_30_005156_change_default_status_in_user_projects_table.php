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
        Schema::table('user_projects', function (Blueprint $table) {
             $table->string('Status', 50)->default('under development')->change();
        $table->string('ReviewStatus', 50)->default('null')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_projects', function (Blueprint $table) {
            //
        });
    }
};
