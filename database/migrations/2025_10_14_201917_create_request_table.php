<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Request\RequestType;
use App\Enums\Request\RequestPriority;
use App\Enums\Request\RequestStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->uuidPrimary(); // PK UUID

            // Identificação
            $table->string('title');
            $table->text('description')->nullable();

            // Classificações (Enums)
            $table->enum('type', array_column(RequestType::cases(), 'value'))
                  ->default(RequestType::INCIDENT->value);

            $table->enum('priority', array_column(RequestPriority::cases(), 'value'))
                  ->default(RequestPriority::NORMAL->value);

            $table->enum('status', array_column(RequestStatus::cases(), 'value'))
                  ->default(RequestStatus::OPEN->value);
            
            // Área (Obrigatório): Pertence a uma Área. Se a Área for deletada, impede a exclusão.
            $table->foreignUuid('area_id')
                  ->constrained('areas')
                  ->restrictOnDelete(); 

            // Requester (Obrigatório): Quem abriu o chamado. Se o User for deletado, define como NULL.
            $table->foreignUuid('requester_id')
                  ->constrained('users')
                  ->nullOnDelete(); 

            $table->dateTime('due_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices 
            $table->index(['status', 'priority', 'type']);
            $table->index('area_id'); 
            $table->index('requester_id'); 
            $table->index('created_at');

            $table->comment('Chamados internos: área e requisitante obrigatórios, múltiplos responsáveis via pivot.');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};