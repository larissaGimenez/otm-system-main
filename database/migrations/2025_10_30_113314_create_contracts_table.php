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
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Relacionamento com o PDV
            $table->foreignUuid('pdv_id')->constrained('pdvs')->cascadeOnDelete();

            $table->date('signed_at')->comment('Data de assinatura');

            // --- Mensalidade Fixa ---
            $table->boolean('has_monthly_fee')->default(false)->comment('Se tem mensalidade');
            
            // Usamos 'decimal' para dinheiro. (ex: 999999.99)
            $table->decimal('monthly_fee_value', 8, 2)->nullable()->comment('Valor da mensalidade');
            
            // Dia do mês (1-31) para o vencimento
            $table->unsignedTinyInteger('monthly_fee_due_day')->nullable()->comment('Dia do vencimento da mensalidade');

            // --- Repasse (Comissão) ---
            $table->boolean('has_commission')->default(false)->comment('Se recebe repasse');
            
            // Usamos 'decimal' para percentual (ex: 15.50 para 15.5%)
            $table->decimal('commission_percentage', 5, 2)->nullable()->comment('% do repasse');

            // --- Dados de Pagamento ---
            $table->string('payment_bank_name')->nullable()->comment('Nome do Banco');
            $table->string('payment_bank_agency')->nullable()->comment('Agência');
            $table->string('payment_bank_account')->nullable()->comment('Conta');
            $table->string('payment_pix_key')->nullable()->comment('Chave PIX');
            
            $table->timestamps();
            $table->softDeletes();

            $table->comment('Contratos de serviço associados aos PDVs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};