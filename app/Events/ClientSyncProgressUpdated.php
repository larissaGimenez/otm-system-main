<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Use ShouldBroadcastNow para tempo real
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientSyncProgressUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $progress;
    private int $companyId;

    /**
     * Create a new event instance.
     */
    public function __construct(int $companyId, int $progress)
    {
        $this->companyId = $companyId;
        $this->progress = $progress;
    }

    /**
     * Get the channels the event should broadcast on.
     * Usamos um Canal Privado para garantir que só o usuário certo receba a atualização.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('company.'.$this->companyId),
        ];
    }
}