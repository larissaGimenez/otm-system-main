<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {

            $table->uuid('id')->primary(); 

            $table->foreignId('equipment_type_id')
                  ->nullable()
                  ->constrained('equipment_types')
                  ->nullOnDelete();

            $table->foreignId('equipment_status_id')
                  ->nullable()
                  ->constrained('equipment_statuses')
                  ->nullOnDelete();

            $table->string('name');

            $table->string('slug')->unique();

            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();

            $table->string('serial_number')->nullable()->unique();
            $table->string('asset_tag')->nullable()->unique();

            $table->json('photos')->nullable();
            $table->json('videos')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['equipment_status_id', 'equipment_type_id']);
            $table->index(['brand', 'model']);
            
            $table->comment('Equipments inventory (assets, devices, etc.)');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
