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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
        $table->string('company_name');
        $table->string('contact_person')->nullable();
        $table->string('email')->unique();
        $table->string('phone')->nullable();
        $table->string('address')->nullable();
        $table->string('password')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
