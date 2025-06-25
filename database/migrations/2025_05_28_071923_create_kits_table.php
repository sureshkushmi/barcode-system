<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_create_kits_table.php

public function up()
{
    Schema::create('kits', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('shipment_id');
        $table->string('barcode')->unique();
        $table->string('name')->nullable(); // Optional: For display
        $table->timestamps();

        $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kits');
    }
};
