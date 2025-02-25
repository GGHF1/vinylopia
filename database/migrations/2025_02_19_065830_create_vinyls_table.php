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
        Schema::create('vinyls', function (Blueprint $table) {
            $table->id('vinyl_id');
            $table->string('title');
            $table->string('artist');
            $table->string('genre');
            $table->string('style');
            $table->string('year');
            $table->string('label');
            $table->string('barcode');
            $table->unsignedBigInteger('release_id');
            $table->text('cover');
            $table->text('secondary_cover');
            $table->unsignedBigInteger('LP');
            $table->text('feat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinyls');
    }
};
