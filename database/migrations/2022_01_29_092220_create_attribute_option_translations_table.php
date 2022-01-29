<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeOptionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_option_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale');
            $table->text('name')->nullable();
            $table->foreignId('attribute_option_id')->constrained()->onDelete('cascade');
            $table->unique(['attribute_option_id', 'locale']);
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
        Schema::dropIfExists('attribute_option_translations');
    }
}
