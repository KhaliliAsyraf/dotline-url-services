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
        Schema::create('url_accessed_infos', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('id_urls')->index();
            $table->string('ip_address')->index();
            $table->text('location')->index();
            $table->string('browser')->nullable()->index();
            $table->timestamps();

            $table->foreign('id_urls')->references('id')->on('urls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_accessed_infos');
    }
};
