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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->time('duracao_atendimento')->nullable();
            $table->time('inicio_atendimento')->nullable();
            $table->time('fim_atendimento')->nullable();
            $table->time('inicio_intervalo')->nullable();
            $table->time('fim_intervalo')->nullable();
            $table->string('domingo')->nullable();
            $table->string('segunda')->nullable();
            $table->string('terca')->nullable();
            $table->string('quarta')->nullable();
            $table->string('quinta')->nullable();
            $table->string('sexta')->nullable();
            $table->string('sabado')->nullable();
            $table->text('link')->nullable();
            //chave estrangeira profissional id
            $table->foreignId('profissional_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
