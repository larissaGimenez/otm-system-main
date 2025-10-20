<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Team\TeamStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->uuidPrimary();              

            // Identificação
            $table->string('name');              
            $table->string('slug')->unique();    
            $table->text('description')->nullable();

            // Relacionamento com áreas
            $table->belongsToUuid('area');     

            // Status (Enum)
            $table->enum('status', array_column(TeamStatus::cases(), 'value'))
                  ->default(TeamStatus::ACTIVE->value);

            $table->timestamps();
            $table->softDeletes();

            // Índices e comentário
            $table->index(['status', 'area_id']);
            $table->comment('Teams or departments linked to organizational areas.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
