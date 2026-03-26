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
        //
        Schema::create('api_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_api_id')->constrained('api_user')->cascadeOnDelete();
            $table->string('street');
            $table->string('suite')->nullable();
            $table->string('city');
            $table->string('zipcode')->nullable();
            $table->timestamps();

            $table->unique('user_api_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('api_address');
    }
};
