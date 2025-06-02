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
        Schema::create('livro_autor', function (Blueprint $table) {
            $table->unsignedBigInteger('livro_cod_l');
            $table->unsignedBigInteger('autor_cod_au');

            $table->foreign('livro_cod_l')
                ->references('cod_l')
                ->on('livros')
                ->onDelete('cascade');

            $table->foreign('autor_cod_au')
                ->references('cod_au')
                ->on('autores')
                ->onDelete('cascade');

            $table->primary(['livro_cod_l', 'autor_cod_au']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livro_autor');
    }
};
