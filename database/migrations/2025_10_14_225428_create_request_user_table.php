<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_user', function (Blueprint $table) {
            $table->belongsToUuid('request', 'requests'); // request_id
            $table->belongsToUuid('user', 'users');       // user_id

            // Evita duplicidade do mesmo user no mesmo chamado
            $table->primary(['request_id', 'user_id']);

            // Índice auxiliar: "todos os chamados onde o usuário X é responsável"
            $table->index('user_id');

            // (Opcional) timestamps para saber quando alguém virou responsável
            $table->timestamps();

            $table->comment('Responsáveis (assignees) de cada request (N:N).');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_user');
    }
};
