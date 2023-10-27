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
        Schema::create('product_inventories', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->default(0);
            $table->foreignId('product_id');
            $table->foreignId('inventory_source_id');
            $table->foreignId('vendor_id')->default(0);
            // $table->foreign('inventory_source_id')->references('id')->on('inventory_sources')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_inventories');
    }
};
