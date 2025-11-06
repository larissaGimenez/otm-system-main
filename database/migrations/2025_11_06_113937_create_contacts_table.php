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
        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relacionamento: Um Contato pertence a um Cliente
            $table->foreignUuid('client_id')
                  ->constrained('clients')
                  ->cascadeOnDelete();

            // Informações do Contato
            $table->string('name', 100);
            $table->string('type', 50)->comment('Usa o Enum ContactType');
            
            $table->string('email')->nullable();
            $table->string('phone_primary')->nullable();
            $table->string('phone_secondary')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices para busca rápida
            $table->index('client_id');
            $table->index('name');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};