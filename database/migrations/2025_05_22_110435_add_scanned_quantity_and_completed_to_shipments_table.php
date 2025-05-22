<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('shipments', function (Blueprint $table) {
        $table->integer('scanned_quantity')->default(0);
        $table->boolean('completed')->default(false);
    });
}

public function down()
{
    Schema::table('shipments', function (Blueprint $table) {
        $table->dropColumn(['scanned_quantity', 'completed']);
    });
}

};
