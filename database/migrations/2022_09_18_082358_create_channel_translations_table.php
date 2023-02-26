<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('home_page_content')->nullable();
            $table->text('footer_content')->nullable();
            $table->text('maintenance_mode_text')->nullable();
            $table->json('home_seo')->nullable();
            $table->timestamps();

            $table->unique(['channel_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_translations');
    }
};
