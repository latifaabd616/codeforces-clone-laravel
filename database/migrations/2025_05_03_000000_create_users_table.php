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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->nullable()->constrained('levels')->OnDelete('set null')->default(1);
            $table->foreignId('type_id')->nullable()->constrained('types')->OnDelete('set null')->default(1);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            
            $table->timestamp('email_verified_at')->nullable();
            $table->string('Avatar', 255)->nullable();
            $table->string('Biography', 255)->nullable();
            $table->date('RegistrationDate')->default(now());
            $table->boolean('Is_Admin')->default(false);
           $table->integer('TotalXP')->default(0);
           $table->integer('rank')->default(0);

            $table->rememberToken();
            $table->timestamps();
 
    });
}
      

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
