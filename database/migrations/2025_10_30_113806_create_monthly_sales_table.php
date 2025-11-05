<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_sales', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('contract_id')
                  ->constrained('contracts')
                  ->cascadeOnDelete();

            // Período
            $table->unsignedSmallInteger('year');   
            $table->unsignedTinyInteger('month'); 

            $table->decimal('gross_sales_value', 12, 2)->comment('Valor de Venda Bruto');
            $table->decimal('net_sales_value', 12, 2)->nullable()->comment('Valor de Venda Líquido');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['contract_id', 'year', 'month']);
            $table->index(['year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_sales');
    }
};
