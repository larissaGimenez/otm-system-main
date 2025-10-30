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
        Schema::create('monthly_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Relacionamentos
            $table->foreignUuid('pdv_id')->constrained('pdvs')->cascadeOnDelete();
            $table->foreignUuid('contract_id')->constrained('contracts')->cascadeOnDelete();

            // Período (Mês/Ano)
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');

            // Valores de Faturamento
            $table->decimal('gross_sales_value', 10, 2)->comment('Valor de Venda Bruto');
            $table->decimal('net_sales_value', 10, 2)->nullable()->comment('Valor de Venda Líquido');

            $table->timestamps();
            $table->softDeletes();

            // Impede entradas duplicadas para o mesmo PDV no mesmo mês/ano
            $table->unique(['pdv_id', 'year', 'month']);

            $table->comment('Registros de faturamento mensal dos PDVs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_sales');
    }
};