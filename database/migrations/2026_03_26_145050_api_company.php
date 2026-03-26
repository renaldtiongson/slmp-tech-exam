<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('api_company', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_api_id')->constrained('api_user')->cascadeOnDelete();
            $table->string('name');
            $table->string('catch_phrase')->nullable();
            $table->string('bs')->nullable();
            $table->timestamps();

            $table->unique('user_api_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_company');
    }
};
