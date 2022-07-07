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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->text('rid')->nullable();
            $table->foreignIdFor(App\Models\Site::class)->nullable();
            $table->text('anonymous_id')->nullable();
            $table->boolean('is_first_visit')->nullable();
            $table->text('type')->nullable();
            $table->text('title')->nullable();
            $table->text('url')->nullable();
            $table->text('path')->nullable();
            $table->text('fragment')->nullable();
            $table->text('query')->nullable();
            $table->text('host')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('browser')->nullable();
            $table->text('browser_version')->nullable();
            $table->text('platform')->nullable();
            $table->text('platform_version')->nullable();
            $table->text('device')->nullable();
            $table->text('language')->nullable();
            $table->text('referrer')->nullable();
            $table->text('timezone')->nullable();
            $table->text('country')->nullable();
            $table->json('screen')->nullable();
            $table->json('window')->nullable();
            $table->json('utm')->nullable();
            $table->text('duration')->nullable();
            $table->text('scroll')->nullable();
            $table->text('custom_domain')->nullable();
            $table->text('custom_variable')->nullable();
            $table->text('ts')->nullable();
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
        Schema::dropIfExists('records');
    }
};
