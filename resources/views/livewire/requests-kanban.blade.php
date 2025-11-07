 
<div class="h-100 d-flex flex-column">
    {{-- 1. FILTROS (Altura Fixa) --}}
    <div class="flex-shrink-0 mb-3">
        <div class="container-fluid">
            <div class="p-3 bg-light rounded">
                <div class="row g-2 align-items-center">
                    {{-- Filtro: Ver --}}
                    <div class="col-md-3">
                        <label for="filterUser" class="form-label small mb-1">Ver</label>
                        <select class="form-select form-select-sm" id="filterUser" wire:model.live="filterUser">
                            <option value="todos">Todos os chamados</option>
                            <option value="meus">Meus chamados</option>
                        </select>
                    </div>
                    
                    {{-- Filtro: Área --}}
                    <div class="col-md-3">
                        <label for="filterArea" class="form-label small mb-1">Área</label>
                        <select class="form-select form-select-sm" id="filterArea" wire:model.live="filterArea">
                            <option value="todas">Todas as áreas</option>
                            @foreach($allAreas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Filtro: Buscar --}}
                    <div class="col-md-6">
                        <label for="search" class="form-label small mb-1">Buscar</label>
                        <input 
                            type="text" 
                            class="form-control form-control-sm" 
                            id="search"
                            placeholder="Buscar por título..."
                            wire:model.live.debounce.300ms="search"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. KANBAN BOARD (Área Rolável) --}}
    <div class="flex-grow-1 position-relative" style="min-height: 0;">
        <div class="position-absolute top-0 start-0 w-100 h-100">
            <div 
                id="kanban-container" 
                class="h-100 overflow-auto"
                style="overflow-x: auto; overflow-y: hidden;"
            >
                <div 
                    class="d-inline-flex h-100 p-3 gap-3" 
                    style="min-width: max-content;"
                    id="kanban-board"
                >
                    {{-- Loop nas COLUNAS --}}
                    @foreach($statuses as $status)
                        <div 
                            class="kanban-column d-flex flex-column" 
                            style="width: 350px; max-height: 100%;"
                        >
                            {{-- Cabeçalho da Coluna --}}
                            <div class="p-3 bg-light rounded-top flex-shrink-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill bg-{{ $status->colors() }}">&nbsp;</span>
                                        <h5 class="mb-0 fw-bold">{{ $status->getLabel() }}</h5>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill">
                                        {{ $requestsByStatus[$status->value]->count() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Corpo da Coluna (Cards Arrastáveis) --}}
                            <div 
                                class="kanban-column-body bg-white rounded-bottom border border-top-0 p-3 flex-grow-1 overflow-auto"
                                data-status="{{ $status->value }}"
                                style="border-color: #dee2e6 !important;"
                            >
                                @forelse($requestsByStatus[$status->value] as $requestItem)
                                    {{-- Card do Kanban --}}
                                    <div 
                                        class="card shadow-sm mb-3 kanban-card" 
                                        data-id="{{ $requestItem->id }}"
                                        style="cursor: grab;"
                                    >
                                        <div class="card-body">
                                            {{-- Título --}}
                                            <a 
                                                href="{{ route('requests.show', $requestItem) }}" 
                                                class="text-decoration-none text-dark"
                                            >
                                                <h6 class="card-title fw-bold mb-2">
                                                    {{ $requestItem->title }}
                                                </h6>
                                            </a>

                                            {{-- Prioridade e Tipo --}}
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge rounded-pill bg-{{ $requestItem->priority->colors() }}">
                                                    {{ $requestItem->priority->getLabel() }}
                                                </span>
                                                <span class="small text-muted">
                                                    {{ $requestItem->pdv->name ?? $requestItem->type->getLabel() }}
                                                </span>
                                            </div>
                                            
                                            {{-- Solicitante e Área --}}
                                            <div class="small text-muted border-top pt-2 mt-2">
                                                <p class="mb-1">
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $requestItem->requester->name ?? 'N/A' }}
                                                </p>
                                                <p class="mb-0">
                                                    <i class="bi bi-folder2-open me-1"></i>
                                                    {{ $requestItem->area->name ?? 'N/A' }}
                                                </p>
                                            </div>

                                            {{-- Data e Atribuídos --}}
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <span class="small text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $requestItem->created_at->format('d/m/Y') }}
                                                </span>
                                                
                                                <div class="d-flex">
                                                    @foreach($requestItem->assignees as $assignee)
                                                        <span 
                                                            class="avatar-initials-small bg-secondary text-white" 
                                                            title="{{ $assignee->name }}"
                                                        >
                                                            {{ Str::substr($assignee->name, 0, 2) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>

                                            {{-- Ações --}}
                                            <div class="text-end border-top pt-2 mt-2">
                                                @can('update', $requestItem)
                                                    <a 
                                                        href="{{ route('requests.edit', $requestItem) }}" 
                                                        class="btn btn-link btn-sm p-0 text-secondary" 
                                                        title="Editar"
                                                    >
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('delete', $requestItem)
                                                    <form 
                                                        action="{{ route('requests.destroy', $requestItem) }}" 
                                                        method="POST" 
                                                        class="d-inline" 
                                                        onsubmit="return confirm('Tem certeza?');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button 
                                                            type="submit" 
                                                            class="btn btn-link btn-sm p-0 text-danger ms-1" 
                                                            title="Excluir"
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted small p-3">
                                        Nenhum chamado aqui.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript para Drag & Drop --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa o Sortable em cada coluna
    const columns = document.querySelectorAll('.kanban-column-body');
    
    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            draggable: '.kanban-card',
            ghostClass: 'kanban-ghost-card',
            dragClass: 'kanban-dragging',
            
            onEnd: function(evt) {
                const cardId = evt.item.dataset.id;
                const newStatus = evt.to.dataset.status;
                
                // Chama o método Livewire para atualizar o status
                @this.call('handleStatusUpdate', cardId, newStatus);
            }
        });
    });
});

// Recarrega o Sortable após atualizações do Livewire
Livewire.hook('message.processed', (message, component) => {
    const columns = document.querySelectorAll('.kanban-column-body');
    
    columns.forEach(column => {
        if (column.sortable) {
            column.sortable.destroy();
        }
        
        column.sortable = new Sortable(column, {
            group: 'kanban',
            animation: 150,
            draggable: '.kanban-card',
            ghostClass: 'kanban-ghost-card',
            dragClass: 'kanban-dragging',
            
            onEnd: function(evt) {
                const cardId = evt.item.dataset.id;
                const newStatus = evt.to.dataset.status;
                
                @this.call('handleStatusUpdate', cardId, newStatus);
            }
        });
    });
});
</script>
@endpush