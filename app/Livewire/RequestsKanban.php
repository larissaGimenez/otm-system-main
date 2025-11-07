<?php

namespace App\Livewire;

use App\Enums\Request\RequestStatus;
use App\Models\Request;
use App\Models\Area; // Precisamos disso para o filtro
use Illuminate\Support\Collection;
use Livewire\Component;

class RequestsKanban extends Component
{
    // 1. PROPRIEDADES DOS FILTROS
    public string $filterUser = 'todos'; // 'todos' ou 'meus'
    public string $filterArea = 'todas'; // 'todas' ou um ID de área
    public string $search = '';

    /**
     * O Render agora busca os dados com base nos filtros
     */
    public function render()
    {
        // 1. Busque todos os status (colunas)
        $statuses = collect(RequestStatus::cases());

        // 2. Crie a query base
        $query = Request::with(['pdv', 'area', 'requester', 'assignees']);

        // 3. APLIQUE OS FILTROS
        
        // Filtro de Usuário
        if ($this->filterUser === 'meus') {
            $query->where(function($q) {
                $q->where('requester_id', auth()->id())
                  ->orWhereHas('assignees', fn($sq) => $sq->where('user_id', auth()->id()));
            });
        }

        // Filtro de Área
        if ($this->filterArea !== 'todas') {
            $query->where('area_id', $this->filterArea);
        }

        // Filtro de Pesquisa
        if (!empty($this->search)) {
            // Adicione mais campos se quiser (ex: pdv.name, requester.name)
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        // 4. Execute a query e agrupe
        $requests = $query->get()->groupBy('status.value');

        // 5. Mapeie os resultados (para garantir colunas vazias)
        $requestsByStatus = $statuses->mapWithKeys(function ($status) use ($requests) {
            return [
                $status->value => $requests->get($status->value, collect())
            ];
        });

        // 6. Passe tudo para a view do Livewire
        return view('livewire.requests-kanban', [
            'statuses' => $statuses,
            'requestsByStatus' => $requestsByStatus,
            'allAreas' => Area::orderBy('name')->get() // Passa as áreas para o select de filtro
        ]);
    }

    // No seu RequestsKanban.php (componente Livewire)

    public function handleStatusUpdate($requestId, $newStatus)
    {
        try {
            $request = Request::findOrFail($requestId);
            
            // Verifica permissão
            $this->authorize('update', $request);
            
            // Atualiza o status
            $request->status = $newStatus;
            $request->save();
            
            // Recarrega os dados
            $this->loadRequests();
            
            // Mensagem de sucesso
            session()->flash('success', 'Status atualizado com sucesso!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar status: ' . $e->getMessage());
        }
    }
}