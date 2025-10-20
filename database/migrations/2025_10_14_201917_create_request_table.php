<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Request\RequestType;
use App\Enums\Request\RequestPriority;
use App\Enums\Request\RequestStatus;

return new class extends Migration
{
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

            // Relacionamentos
            $table->belongsToUuid('area');                      // OBRIGATÓRIO
            $table->belongsToUuid('team', 'teams', true, 'set null'); // OPCIONAL (pode ser null)
            $table->belongsToUuid('requester', 'users');        // quem abriu

            // Prazos
            $table->dateTime('due_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices úteis
            $table->index(['status', 'priority', 'type']);
            $table->index(['area_id', 'team_id']);
            $table->index('created_at');

            $table->comment('Chamados internos: área obrigatória, time opcional, múltiplos responsáveis via pivot.');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
