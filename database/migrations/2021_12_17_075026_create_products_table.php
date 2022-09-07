<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreignId('attribute_family_id')->onDelete('restrict');
            $table->string('sku')->unique();
            $table->string('type');
            $table->timestamps();
        });

        // needs to run query twice when referencing the foreign key on same table
        // Schema::table('products', function (Blueprint $table) {
        // $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
        // });

        // Schema::create('product_categories', function (Blueprint $table) {
        //     // $table->integer('product_id')->unsigned();
        //     // $table->integer('category_id')->unsigned();
        //     // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        //     // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        // });

        // Schema::create('product_relations', function (Blueprint $table) {
        //     $table->integer('parent_id')->unsigned();
        //     $table->integer('child_id')->unsigned();
        //     $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
        //     $table->foreign('child_id')->references('id')->on('products')->onDelete('cascade');
        // });

        Schema::create('product_super_attributes', function (Blueprint $table) {
            $table->foreignId('product_id')->onDelete('cascade');
            $table->foreignId('attribute_id')->onDelete('restrict');

            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            // $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('restrict');
        });

        // Schema::create('product_up_sells', function (Blueprint $table) {
        //     $table->integer('parent_id')->unsigned();
        //     $table->integer('child_id')->unsigned();
        //     $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
        //     $table->foreign('child_id')->references('id')->on('products')->onDelete('cascade');
        // });

        // Schema::create('product_cross_sells', function (Blueprint $table) {
        //     $table->integer('parent_id')->unsigned();
        //     $table->integer('child_id')->unsigned();
        //     $table->foreign('parent_id')->references('id')->on('products')->onDelete('cascade');
        //     $table->foreign('child_id')->references('id')->on('products')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('product_cross_sells');

        // Schema::dropIfExists('product_up_sells');

        Schema::dropIfExists('product_super_attributes');

        // Schema::dropIfExists('product_relations');

        // Schema::dropIfExists('product_categories');

        Schema::dropIfExists('products');
    }
}
