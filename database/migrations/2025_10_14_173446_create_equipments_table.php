<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Equipment\EquipmentType;
use App\Enums\Equipment\EquipmentStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->uuidPrimary(); 

            // Identificação
            $table->string('name', 50);
            $table->string('slug')->unique(); 

            // Classificação (Enums)
            $table->enum('type', array_column(EquipmentType::cases(), 'value'));
            $table->enum('status', array_column(EquipmentStatus::cases(), 'value'))
                  ->default(EquipmentStatus::AVAILABLE->value);

            // Detalhes
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();

            // Identificadores patrimoniais
            $table->string('serial_number')->nullable()->unique();
            $table->string('asset_tag')->nullable()->unique();

            // Mídias
            $table->json('photos')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['status', 'type']);
            $table->index(['brand', 'model']);
            $table->index('name');

            $table->comment('Equipments inventory (assets, devices, etc.)');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
