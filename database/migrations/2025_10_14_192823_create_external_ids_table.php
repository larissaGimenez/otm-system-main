<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_ids', function (Blueprint $table) {
            $table->uuidPrimary();          

            // Relacionamento polimórfico com UUID: item_id (uuid) + item_type (string) + índice
            $table->uuidMorphs('item');

            // Sistema externo e identificador no sistema
            $table->string('system_name');         // ex.: 'erp_totvs', 'rdstation', 'sap'
            $table->string('external_id');         // ID no sistema externo (normalmente string)

            // Metadados opcionais (payload do conector, timestamps remotos, etc.)
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Evita duplicar o mesmo ID no mesmo sistema
            $table->unique(['system_name', 'external_id']);

            // Evita o mesmo item ter dois IDs no mesmo sistema
            $table->unique(['system_name', 'item_type', 'item_id']);

            $table->index('system_name');

            $table->comment('Mapeia itens internos (polimórfico) para IDs de sistemas externos.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_ids');
    }
};
