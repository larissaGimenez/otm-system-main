<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Pdv\PdvType;
use App\Enums\Pdv\PdvStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pdvs', function (Blueprint $table) {
            $table->uuidPrimary();        

            // Identificação
            $table->string('name');
            $table->string('slug')->unique();    
            $table->string('cnpj', 14)->nullable()->unique();
            $table->text('description')->nullable();

            // Classificações (Enums)
            $table->enum('type', array_column(PdvType::cases(), 'value'));
            $table->enum('status', array_column(PdvStatus::cases(), 'value'))
                  ->default(PdvStatus::ACTIVE->value);

            // Endereço básico
            $table->string('street')->nullable();
            $table->string('number', 10)->nullable();
            $table->string('complement')->nullable();

            // Mídias
            $table->json('photos')->nullable();
            $table->json('videos')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices 
            $table->index(['status', 'type']);
            $table->index('name');

            $table->comment('Pontos de venda (PDVs)');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdvs');
    }
};
