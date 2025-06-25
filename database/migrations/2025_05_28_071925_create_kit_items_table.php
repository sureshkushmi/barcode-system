<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_kit_items_table.php

public function up()
{
    Schema::create('kit_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('kit_id');
        $table->unsignedBigInteger('item_id');
        $table->integer('quantity');
        $table->timestamps();

        $table->foreign('kit_id')->references('id')->on('kits')->onDelete('cascade');
        $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kit_items');
    }
};
