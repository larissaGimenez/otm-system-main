<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_installments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Relacionamento com a "capa" da taxa
            $table->foreignUuid('activation_fee_id')->constrained('activation_fees')->cascadeOnDelete();

            $table->unsignedTinyInteger('installment_number')->comment('Número da parcela (1, 2, 3...)');
            $table->decimal('value', 8, 2)->comment('Valor desta parcela');
            $table->date('due_date')->comment('Data de vencimento desta parcela');
            $table->boolean('is_paid')->default(false);
            $table->dateTime('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
            
            // Impede a duplicidade da Parcela "1" para a mesma taxa
            $table->unique(['activation_fee_id', 'installment_number']);

            $table->comment('Parcelas da taxa de ativação');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_installments');
    }
};