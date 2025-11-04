<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Pdv\PdvType;
use App\Enums\Pdv\PdvStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pdvs', function (Blueprint $table) {
            $table->uuidPrimary(); 

            $table->foreignUuid('client_id')
                  ->nullable() 
                  ->constrained('clients') 
                  ->onDelete('set null'); 

            $table->string('name');
            $table->string('slug')->unique(); 
            $table->enum('type', array_column(PdvType::cases(), 'value'));
            $table->enum('status', array_column(PdvStatus::cases(), 'value'));
            $table->text('description')->nullable();

            // Endereço (baseado nas suas blades de PDV)
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();

            // Mídia (baseado nas suas blades de PDV)
            $table->json('photos')->nullable();
            $table->json('videos')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['client_id', 'status', 'type']);
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdvs');
    }
};