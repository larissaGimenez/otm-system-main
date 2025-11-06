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
            $table->string('name', 50);
            $table->string('slug')->unique(); 

            $table->string('type', 50); 
            $table->string('status', 50)
                  ->default(EquipmentStatus::AVAILABLE->value);

            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable()->unique();
            $table->string('asset_tag')->nullable()->unique();
            $table->json('photos')->nullable();
            $table->json('videos')->nullable();
            $table->timestamps();
            $table->softDeletes();
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