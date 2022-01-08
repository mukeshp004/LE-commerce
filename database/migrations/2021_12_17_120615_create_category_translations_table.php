<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            // $table->integer('category_id')->unsigned();
            $table->string('locale');
            // $table->integer('locale_id')->nullable()->unsigned();
            $table->foreignId('locale_id')->constrained()->onDelete('cascade');

            $table->string('display_mode')->default('products_and_description')->nullable();

            $table->unique(['category_id', 'slug', 'locale']);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
                        
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
        Schema::dropIfExists('category_translations');
    }
}
