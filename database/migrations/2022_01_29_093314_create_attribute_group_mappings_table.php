<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeGroupMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_group_mappings', function (Blueprint $table) {
            // $table->id();
            $table->foreignId('attribute_id')->onDelete('cascade');
            $table->foreignId('attribute_group_id')->onDelete('cascade');
            $table->primary(['attribute_id', 'attribute_group_id']);
            $table->integer('position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_group_mappings');
    }
}
