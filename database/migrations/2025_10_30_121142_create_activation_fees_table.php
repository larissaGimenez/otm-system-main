<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activation_fees', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relacionamento 1-para-1 com o Cliente
            $table->foreignUuid('client_id')->unique()->constrained('clients')->cascadeOnDelete();

            // Valor total da taxa de implantação
            $table->decimal('total_value', 10, 2)->default(0)->comment('Valor total da taxa de ativação');

            // Observações gerais
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->comment('Taxas de ativação (custo de implantação) vinculadas aos clientes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activation_fees');
    }
};
