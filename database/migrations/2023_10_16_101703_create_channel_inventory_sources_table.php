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
        Schema::create('channel_inventory_sources', function (Blueprint $table) {            
            $table->foreignId('channel_id')->onDelete('cascade');
            $table->foreignId('inventory_source_id')->onDelete('cascade');
            $table->unique(['channel_id', 'inventory_source_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_inventory_sources');
    }
};
