<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_photo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->unique();
            $table->foreignId('album_id')
                ->constrained('api_album')
                ->cascadeOnDelete();
            $table->string('title');
            $table->string('url');
            $table->string('thumbnail_url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_photo');
    }
};