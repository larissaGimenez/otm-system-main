<div class="h-100 d-flex flex-column">
    <div class="flex-shrink-0 mb-4">
        <div class="container-fluid">
            <div class="ms-0 bg-light rounded ">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <!-- <label class="form-label small mb-1 fw-bold text-muted">Visualização</label> -->
                        <select class="form-select form-select-sm" wire:model.live="filterUser">
                            <option value="todos">Todos os chamados</option>
                            <option value="meus">Meus chamados</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <!-- <label class="form-label small mb-1 fw-bold text-muted">Filtrar por Área</label> -->
                        <select class="form-select form-select-sm" wire:model.live="filterArea">
                            <option value="todas">Todas as áreas</option>
                            @foreach($allAreas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <!-- <label class="form-label small mb-1 fw-bold text-muted">Pesquisa Rápida</label> -->
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" placeholder="Buscar..."
                                wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-grow-1 position-relative" style="min-height:0;">

        <div wire:loading class="position-absolute top-0 start-50 translate-middle-x mt-2 z-3">
            <span class="badge bg-primary shadow-sm py-2 px-3">
                <span class="spinner-border spinner-border-sm me-1"></span>
                Carregando...
            </span>
        </div>

        <div class="position-absolute top-0 start-0 w-100 h-100 px-3 pb-3">
            <div id="kanban-container" class="h-100 d-flex gap-3 overflow-auto pb-2 custom-scrollbar">

                @foreach($statuses as $status)
                    <div x-data="{ expanded: true }" class="h-100">

                        <div class="kanban-column d-flex flex-column"
                            x-bind:class="expanded ? 'kanban-open' : 'kanban-closed'"
                            style="--status-color: var(--bs-{{ $status->colors() }});"
                            @click="if (!expanded) expanded = true">

                            {{-- Cabeçalho (somente coluna aberta) --}}
                            <div class="p-3 border-bottom bg-white rounded-top flex-shrink-0"
                                x-bind:style="expanded ? 'display:block' : 'display:none'">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <div class="d-flex align-items-center gap-2 flex-grow-1">
                                        <span class="badge rounded-circle p-1 bg-{{ $status->colors() }}"
                                            style="width:12px;height:12px;"></span>
                                        <h6 class="mb-0 fw-bold text-uppercase small">
                                            {{ $status->getLabel() }}
                                        </h6>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-secondary border rounded-pill me-2">
                                            {{ $requestsByStatus[$status->value]->count() }}
                                        </span>
                                        <button @click.stop="expanded = false" class="btn btn-sm btn-link text-muted p-0"
                                            type="button">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Corpo da coluna (cards) --}}
                            <div class="kanban-column-body p-2 flex-grow-1 overflow-auto custom-scrollbar"
                                data-status="{{ $status->value }}"
                                x-bind:style="expanded ? 'display:block' : 'display:none'">
                                @forelse($requestsByStatus[$status->value] as $requestItem)
                                    <div class="card shadow-sm mb-2 kanban-card border-0" data-id="{{ $requestItem->id }}"
                                        style="cursor:grab;border-left:4px solid var(--bs-{{ $requestItem->priority->colors() }}) !important;">
                                        <div class="card-body p-3">
                                            <div
                                                class="d-flex justify-content-between align-items-start mb-2 position-relative">
                                                <div>
                                                    <span class="badge bg-light text-muted border rounded-1 fw-normal"
                                                        style="font-size:.7rem;">
                                                        {{ $requestItem->pdv->name ?? $requestItem->type->getLabel() }}
                                                    </span>
                                                    @if($requestItem->due_at)
                                                        <span
                                                            class="badge {{ $requestItem->due_at->isPast() ? 'bg-danger' : 'bg-info' }} rounded-1 fw-normal ms-1"
                                                            style="font-size:.7rem;">
                                                            {{ $requestItem->due_at->format('d/m') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                @if($requestItem->status === \App\Enums\Request\RequestStatus::COMPLETED)
                                                    <div class="dropdown z-3" onclick="event.stopPropagation();">
                                                        <button class="btn btn-link btn-sm p-0 text-muted" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                                            <li>
                                                                <form action="{{ route('requests.archive', $requestItem) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Tem certeza que deseja arquivar este chamado?');">
                                                                    @csrf
                                                                    <button type="submit" class="dropdown-item text-danger small">
                                                                        <i class="bi bi-archive me-2"></i>
                                                                        Arquivar
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>

                                            <a href="{{ route('requests.show', $requestItem) }}"
                                                class="text-decoration-none text-dark stretched-link">
                                                <h6 class="fw-bold mb-2" style="font-size:.95rem;line-height:1.4;">
                                                    {{ Str::limit($requestItem->title, 60) }}
                                                </h6>
                                            </a>

                                            <div
                                                class="d-flex justify-content-between align-items-end mt-3 pt-2 border-top border-light">
                                                <div class="text-muted small" style="font-size:.75rem;">
                                                    <span class="fw-bold">#{{ substr($requestItem->id, 0, 6) }}</span><br>
                                                    <span>{{ $requestItem->created_at->diffForHumans(null, true) }}</span>
                                                </div>

                                                <div class="d-flex ps-2 position-relative z-2">
                                                    @foreach($requestItem->assignees->take(3) as $assignee)
                                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center border border-white"
                                                            style="width:24px;height:24px;font-size:.65rem;margin-left:-8px;"
                                                            title="{{ $assignee->name }}">
                                                            {{ Str::substr($assignee->name, 0, 2) }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted small py-5 opacity-50">
                                        <i class="bi bi-inbox-fill fs-4 d-block mb-2"></i>
                                        Vazio
                                    </div>
                                @endforelse
                            </div>

                            {{-- Coluna colapsada (overlay Jira/ClickUp) --}}
                            <div class="kanban-closed-content" x-bind:style="expanded ? 'display:none' : 'display:flex'">
                                <span class="badge bg-{{ $status->colors() }} rounded-pill mb-3 py-2 shadow-sm">
                                    {{ $requestsByStatus[$status->value]->count() }}
                                </span>
                                <div class="vertical-text fw-bold text-muted text-uppercase small">
                                    {{ $status->getLabel() }}
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            let sortables = [];

            const initKanban = () => {
                sortables.forEach(s => s.destroy());
                sortables = [];

                document.querySelectorAll('.kanban-column-body').forEach(column => {
                    sortables.push(new Sortable(column, {
                        group: 'kanban',
                        animation: 150,
                        delay: 100,
                        delayOnTouchOnly: true,
                        draggable: '.kanban-card',
                        ghostClass: 'kanban-ghost-card',
                        dragClass: 'kanban-dragging',
                        onEnd: evt => {
                            if (evt.from === evt.to) return;
                            @this.handleStatusUpdate(evt.item.dataset.id, evt.to.dataset.status);
                        }
                    }));
                });
            };

            initKanban();

            Livewire.hook('element.updated', () => {
                initKanban();
            });
        });
    </script>
@endpush