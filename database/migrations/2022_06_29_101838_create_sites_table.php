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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->nullable();
            $table->text('name')->nullable();
            $table->text('domain')->nullable();
            $table->boolean('public')->default(true);
            $table->boolean('public_list')->default(true);
            $table->text('ignore_paths')->nullable();
            $table->text('domain_whitelist')->nullable();
            $table->boolean('collect_dnt')->default(true);
            $table->text('hash_mode')->nullable();
            $table->json('settings')->nullable();
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
        Schema::dropIfExists('sites');
    }
};
