<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')
                  ->constrained('clients')
                  ->cascadeOnDelete();
            $table->date('signed_at')->nullable();
            $table->boolean('has_monthly_fee')->default(false);
            $table->decimal('monthly_fee_value', 10, 2)->nullable();
            $table->unsignedTinyInteger('monthly_fee_due_day')->nullable();
            $table->boolean('has_commission')->default(false);
            $table->decimal('commission_percentage', 5, 2)->nullable();
            $table->string('pdf_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['client_id', 'signed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
