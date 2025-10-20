<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_pdv', function (Blueprint $table) {
            
            $table->belongsToUuid('pdv', 'pdvs');              
            $table->belongsToUuid('equipment', 'equipments'); 
            $table->primary(['pdv_id', 'equipment_id']);

            // Índice 
            $table->index('equipment_id');

            $table->timestamps();
            $table->comment('Pivot: equipments <-> pdvs');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_pdv');
    }
};
