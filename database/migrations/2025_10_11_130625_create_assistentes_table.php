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
        Schema::create('assistentes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('gpt_api_key')->nullable();
            $table->text('gpt_assistent_id')->nullable();
            $table->text('prompt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistentes');
    }
};
