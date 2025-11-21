<?php

namespace App\Livewire;

use App\Enums\Request\RequestStatus;
use App\Models\Request;
use App\Models\Area;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RequestsKanban extends Component
{
    use AuthorizesRequests; 

    public string $filterUser = 'todos'; 
    public string $filterArea = 'todas'; 
    public string $search = '';

    public function render()
    {
        $statuses = collect(RequestStatus::cases());

        $query = Request::with(['pdv', 'area', 'requester', 'assignees'])
            ->orderBy('created_at', 'desc'); 

        if ($this->filterUser === 'meus') {
            $query->where(function($q) {
                $q->where('requester_id', auth()->id())
                  ->orWhereHas('assignees', fn($sq) => $sq->where('user_id', auth()->id()));
            });
        }

        if ($this->filterArea !== 'todas') {
            $query->where('area_id', $this->filterArea);
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%'); 
            });
        }

        $requests = $query->get()->groupBy(fn($req) => $req->status->value);
        $requestsByStatus = $statuses->mapWithKeys(function ($status) use ($requests) {
            return [
                $status->value => $requests->get($status->value, collect())
            ];
        });

        return view('livewire.requests-kanban', [
            'statuses' => $statuses,
            'requestsByStatus' => $requestsByStatus,
            'allAreas' => Area::orderBy('name')->get()
        ]);
    }

    public function handleStatusUpdate($requestId, $newStatusValue)
    {
        try {
            $request = Request::findOrFail($requestId);
            $this->authorize('update', $request);
            $newStatusEnum = RequestStatus::tryFrom($newStatusValue);

            if ($newStatusEnum) {
                $request->status = $newStatusEnum;
                $request->save();
                $this->dispatch('status-updated', message: 'Status alterado com sucesso!');
            }

        } catch (\Exception $e) {
            $this->dispatch('refresh-kanban'); 
            session()->flash('error', 'Erro ao atualizar: ' . $e->getMessage());
        }
    }
}