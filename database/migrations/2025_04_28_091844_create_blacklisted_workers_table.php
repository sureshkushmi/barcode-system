<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlacklistedWorkersTable extends Migration
{
    public function up()
    {
        Schema::create('blacklisted_workers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('reason'); // Reason for blacklisting
            $table->text('proof')->nullable(); // (Optional) Link to file or description
            $table->unsignedBigInteger('reported_by'); // Who reported it (company or member ID)
            $table->boolean('approved')->default(false); // Superadmin must approve
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blacklisted_workers');
    }
}
