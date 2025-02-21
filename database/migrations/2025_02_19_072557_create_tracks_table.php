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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id('track_id');
            $table->unsignedBigInteger('vinyl_id');
            $table->integer('track_number'); // Track number (1, 2, 3, etc.)
            $table->string('title'); // Track title
            $table->string('position'); // Position (e.g., A1, A2, B1)
            $table->string('duration'); // Duration (e.g., 3:45)
            $table->timestamps();
            $table->foreign('vinyl_id')->references('vinyl_id')->on('vinyls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};

