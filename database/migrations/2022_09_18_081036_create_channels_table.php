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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('timezone')->nullable();
            $table->string('theme')->nullable();
            $table->string('hostname')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('home_page_content')->nullable();
            $table->text('footer_content')->nullable();

            $table->foreignId('locale_id')->onDelete('cascade');
            $table->foreignId('currency_id')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('channel_locales', function (Blueprint $table) {
            $table->foreignId('channel_id')->onDelete('cascade');
            $table->foreignId('locale_id')->onDelete('cascade');

            $table->primary(['channel_id', 'locale_id']);
        });

        Schema::create('channel_currencies', function (Blueprint $table) {
            $table->foreignId('channel_id')->onDelete('cascade');
            $table->foreignId('currency_id')->onDelete('cascade');
            $table->primary(['channel_id', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_currencies');
        Schema::dropIfExists('channel_locales');
        Schema::dropIfExists('channels');
    }
};
