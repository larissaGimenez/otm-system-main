<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Pdv\FeePaymentMethod;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activation_fees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Relacionamento 1-para-1 com o PDV
            // 'unique' garante que um PDV só pode ter uma taxa
            $table->foreignUuid('pdv_id')->unique()->constrained('pdvs')->cascadeOnDelete();

            // Informações do 'brainstorming'
            $table->string('payment_method')->comment('Forma de pagamento (boleto, cartao, pix...)');
            $table->unsignedTinyInteger('installments_count')->default(1)->comment('Quantas parcelas');
            $table->date('due_date')->nullable()->comment('Data de vencimento geral (para monitoramento)');
            
            $table->text('notes')->nullable()->comment('Observações gerais');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->comment('Taxas de ativação (custo de implantação) do PDV');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activation_fees');
    }
};