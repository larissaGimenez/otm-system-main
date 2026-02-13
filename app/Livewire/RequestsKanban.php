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

    // View and UI state
    public string $viewMode = 'kanban'; // 'kanban' ou 'list'
    public bool $showFilters = false;

    // Existing filters
    public string $filterUser = 'todos';
    public string $filterArea = 'todas';
    public string $search = '';

    // New advanced filters
    public array $filterStatuses = [];
    public array $filterAreas = [];
    public array $filterPriorities = [];
    public ?string $filterUrgency = null;

    public function render()
    {
        $statuses = collect(RequestStatus::cases());

        $query = Request::query()
            ->notArchived() // Excluir chamados arquivados
            ->with(['pdv', 'area', 'requester', 'assignees'])
            ->orderBy('created_at', 'desc');

        if ($this->filterUser === 'meus') {
            $query->where(function ($q) {
                $q->where('requester_id', auth()->id())
                    ->orWhereHas('assignees', fn($sq) => $sq->where('user_id', auth()->id()));
            });
        }

        if ($this->filterArea !== 'todas') {
            $query->where('area_id', $this->filterArea);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
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
            'allAreas' => Area::orderBy('name')->get(),
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

    public function toggleView()
    {
        $this->viewMode = $this->viewMode === 'kanban' ? 'list' : 'kanban';
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearFilters()
    {
        $this->filterStatuses = [];
        $this->filterAreas = [];
        $this->filterPriorities = [];
        $this->filterUrgency = null;
        $this->search = '';
        $this->filterUser = 'todos';
        $this->filterArea = 'todas';
    }

    public function getActiveFiltersCountProperty()
    {
        $count = 0;
        if (!empty($this->filterStatuses))
            $count++;
        if (!empty($this->filterAreas))
            $count++;
        if (!empty($this->filterPriorities))
            $count++;
        if ($this->filterUrgency)
            $count++;
        if ($this->search)
            $count++;
        if ($this->filterUser !== 'todos')
            $count++;
        if ($this->filterArea !== 'todas')
            $count++;
        return $count;
    }
}