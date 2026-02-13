<div class="h-100 d-flex flex-column">
    {{-- Modern Compact Header --}}
    <div class="flex-shrink-0 mb-3">
        <div class="bg-white shadow-sm rounded-3 p-3">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                {{-- Left: Title --}}
                <div class="d-flex align-items-center gap-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-kanban me-2 text-primary"></i>
                        Chamados
                    </h5>
                </div>

                {{-- Center: Search --}}
                <div class="flex-grow-1" style="max-width: 400px;">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                            placeholder="Buscar chamados..." wire:model.live.debounce.300ms="search">
                    </div>
                </div>

                {{-- Right: Actions --}}
                <div class="d-flex align-items-center gap-2">
                    {{-- Filter Button --}}
                    <button type="button"
                        class="btn btn-sm {{ $showFilters ? 'btn-primary' : 'btn-outline-primary' }} position-relative"
                        wire:click="toggleFilters">
                        <i class="bi bi-funnel-fill"></i>
                        Filtros
                        @if($this->activeFiltersCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $this->activeFiltersCount }}
                            </span>
                        @endif
                    </button>

                    {{-- View Toggle --}}
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button"
                            class="btn {{ $viewMode === 'kanban' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="toggleView" title="Visualização Kanban">
                            <i class="bi bi-kanban"></i>
                        </button>
                        <button type="button"
                            class="btn {{ $viewMode === 'list' ? 'btn-primary' : 'btn-outline-primary' }}"
                            wire:click="toggleView" title="Visualização em Lista">
                            <i class="bi bi-list-ul"></i>
                        </button>
                    </div>

                    {{-- SLA Info --}}
                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                        data-bs-placement="left" data-bs-html="true"
                        title="<strong>Regras de SLA (Horas Úteis: Seg-Sex, 8h-18h)</strong><br><br>
                                   <strong>Em Aberto:</strong><br>🟠 Laranja após 2h úteis<br>🔴 Vermelho após 6h úteis<br><br>
                                   <strong>Em Andamento:</strong><br>🟠 Laranja após 24h úteis<br>🔴 Vermelho após 48h úteis<br><br>
                                   <strong>Solução Longa:</strong><br>🟠 Laranja após 3 dias úteis<br>🔴 Vermelho após 5 dias úteis">
                        <i class="bi bi-info-circle"></i>
                    </button>
                </div>
            </div>

            {{-- Filter Panel (Collapsible) --}}
            <div class="collapse {{ $showFilters ? 'show' : '' }}" wire:transition>
                <hr class="my-3">
                <div class="row g-3">
                    {{-- Quick Filters --}}
                    <div class="col-md-3">
                        <label class="form-label small mb-1 fw-bold text-muted">Visualização</label>
                        <select class="form-select form-select-sm" wire:model.live="filterUser">
                            <option value="todos">Todos os chamados</option>
                            <option value="meus">Meus chamados</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small mb-1 fw-bold text-muted">Área</label>
                        <select class="form-select form-select-sm" wire:model.live="filterArea">
                            <option value="todas">Todas as áreas</option>
                            @foreach($allAreas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 d-flex align-items-end">
                        @if($this->activeFiltersCount > 0)
                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="clearFilters">
                                <i class="bi bi-x-circle me-1"></i>
                                Limpar Filtros ({{ $this->activeFiltersCount }})
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Area: Kanban or List --}}
    <div class="flex-grow-1 position-relative" style="min-height:0;">

        <div wire:loading class="position-absolute top-0 start-50 translate-middle-x mt-2 z-3">
            <span class="badge bg-primary shadow-sm py-2 px-3">
                <span class="spinner-border spinner-border-sm me-1"></span>
                Carregando...
            </span>
        </div>

        @if($viewMode === 'kanban')
            {{-- Kanban View --}}
            <div class="position-absolute top-0 start-0 w-100 h-100 pb-3">
                <div id="kanban-container" class="h-100 d-flex gap-3 overflow-auto pb-2">

                    @foreach($statuses as $status)
                        <div x-data="{ expanded: true }" class="h-100">

                            <div class="kanban-column d-flex flex-column bg-white shadow-sm rounded"
                                x-bind:class="expanded ? 'kanban-open' : 'kanban-closed'"
                                style="--status-color: var(--bs-{{ $status->colors() }});"
                                @click="if (!expanded) expanded = true">

                                {{-- Cabeçalho --}}
                                <div class="p-3 border-bottom flex-shrink-0"
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
                                <div class="kanban-column-body p-2 flex-grow-1 overflow-auto" data-status="{{ $status->value }}"
                                    x-bind:style="expanded ? 'display:block' : 'display:none'">
                                    @forelse($requestsByStatus[$status->value] as $requestItem)
                                        <div class="card shadow-sm mb-2 kanban-card border-0 {{ $requestItem->getUrgencyClass() }}"
                                            data-id="{{ $requestItem->id }}"
                                            style="cursor:grab;border-left:4px solid var(--bs-{{ $requestItem->priority->colors() }}) !important;">
                                            <div class="card-body p-3">
                                                {{-- Top: Time Badge + Menu --}}
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    {{-- Large Time Badge --}}
                                                    <div class="flex-grow-1">
                                                        @php
                                                            $timeOpen = $requestItem->created_at->diffForHumans(null, true, true);
                                                            $urgency = $requestItem->getUrgencyLevel();
                                                            $badgeClass = match ($urgency) {
                                                                'danger' => 'bg-danger text-white',
                                                                'warning' => 'bg-warning text-dark',
                                                                default => 'bg-light text-muted border'
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $badgeClass }} rounded-2 fw-bold px-2 py-1"
                                                            style="font-size: 0.85rem;">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ $timeOpen }}
                                                        </span>

                                                        {{-- SLA Urgency Badge --}}
                                                        @if($requestItem->getUrgencyBadge())
                                                            {!! $requestItem->getUrgencyBadge() !!}
                                                        @endif
                                                    </div>

                                                    {{-- Archive Menu (only for completed) --}}
                                                    @if($requestItem->status === \App\Enums\Request\RequestStatus::COMPLETED)
                                                        <div class="dropdown" onclick="event.stopPropagation();">
                                                            <button class="btn btn-link btn-sm p-0 text-muted" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bi bi-three-dots-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                                <li>
                                                                    <form action="{{ route('requests.archive', $requestItem) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Tem certeza que deseja arquivar este chamado?');">
                                                                        @csrf
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="bi bi-archive me-2"></i>
                                                                            Arquivar
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Title --}}
                                                <a href="{{ route('requests.show', $requestItem) }}"
                                                    class="text-decoration-none text-dark">
                                                    <h6 class="fw-bold mb-2 lh-sm" style="font-size: 0.95rem;">
                                                        {{ Str::limit($requestItem->title, 60) }}
                                                    </h6>
                                                </a>

                                                {{-- PDV/Type Info --}}
                                                <div class="mb-2">
                                                    <small class="text-muted d-block">
                                                        <i class="bi bi-tag me-1"></i>
                                                        {{ $requestItem->pdv->name ?? $requestItem->type->getLabel() }}
                                                    </small>
                                                </div>

                                                {{-- Footer: Assignees --}}
                                                <div
                                                    class="d-flex align-items-center justify-content-between pt-2 border-top border-light">
                                                    <div class="d-flex gap-1">
                                                        @forelse($requestItem->assignees->take(3) as $assignee)
                                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center border border-primary"
                                                                style="width: 28px; height: 28px; font-size: 0.7rem; font-weight: 600;"
                                                                title="{{ $assignee->name }}">
                                                                {{ strtoupper(Str::substr($assignee->name, 0, 2)) }}
                                                            </div>
                                                        @empty
                                                            <small class="text-muted fst-italic">
                                                                <i class="bi bi-person-x me-1"></i>Sem responsável
                                                            </small>
                                                        @endforelse

                                                        @if($requestItem->assignees->count() > 3)
                                                            <div class="rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center"
                                                                style="width: 28px; height: 28px; font-size: 0.65rem;">
                                                                +{{ $requestItem->assignees->count() - 3 }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- Due Date (if exists) --}}
                                                    @if($requestItem->due_at)
                                                        <span
                                                            class="badge {{ $requestItem->due_at->isPast() ? 'bg-danger' : 'bg-info' }} rounded-1"
                                                            style="font-size: 0.7rem;">
                                                            <i class="bi bi-calendar-event me-1"></i>
                                                            {{ $requestItem->due_at->format('d/m') }}
                                                        </span>
                                                    @endif
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

                                {{-- Coluna colapsada --}}
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
        @else
            {{-- List View --}}
            <div class="position-absolute top-0 start-0 w-100 h-100 overflow-auto pb-3">
                <div class="bg-white shadow-sm rounded-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="fw-bold small text-muted">TEMPO</th>
                                    <th class="fw-bold small text-muted">STATUS</th>
                                    <th class="fw-bold small text-muted">TÍTULO</th>
                                    <th class="fw-bold small text-muted">PDV/TIPO</th>
                                    <th class="fw-bold small text-muted">RESPONSÁVEIS</th>
                                    <th class="fw-bold small text-muted text-end">AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statuses as $status)
                                    @foreach($requestsByStatus[$status->value] as $requestItem)
                                        <tr class="{{ $requestItem->getUrgencyClass() }}"
                                            style="border-left: 4px solid var(--bs-{{ $requestItem->priority->colors() }});">
                                            {{-- Time --}}
                                            <td style="width: 120px;">
                                                @php
                                                    $timeOpen = $requestItem->created_at->diffForHumans(null, true, true);
                                                    $urgency = $requestItem->getUrgencyLevel();
                                                    $badgeClass = match ($urgency) {
                                                        'danger' => 'bg-danger text-white',
                                                        'warning' => 'bg-warning text-dark',
                                                        default => 'bg-light text-muted'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }} fw-bold">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $timeOpen }}
                                                </span>
                                            </td>

                                            {{-- Status --}}
                                            <td style="width: 150px;">
                                                <span class="badge bg-{{ $status->colors() }} rounded-pill">
                                                    {{ $status->getLabel() }}
                                                </span>
                                            </td>

                                            {{-- Title --}}
                                            <td>
                                                <a href="{{ route('requests.show', $requestItem) }}"
                                                    class="text-decoration-none text-dark fw-bold">
                                                    {{ Str::limit($requestItem->title, 80) }}
                                                </a>
                                            </td>

                                            {{-- PDV/Type --}}
                                            <td style="width: 200px;">
                                                <small class="text-muted">
                                                    {{ $requestItem->pdv->name ?? $requestItem->type->getLabel() }}
                                                </small>
                                            </td>

                                            {{-- Assignees --}}
                                            <td style="width: 150px;">
                                                <div class="d-flex gap-1">
                                                    @forelse($requestItem->assignees->take(3) as $assignee)
                                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center border border-primary"
                                                            style="width: 24px; height: 24px; font-size: 0.65rem; font-weight: 600;"
                                                            title="{{ $assignee->name }}">
                                                            {{ strtoupper(Str::substr($assignee->name, 0, 2)) }}
                                                        </div>
                                                    @empty
                                                        <small class="text-muted fst-italic">—</small>
                                                    @endforelse
                                                </div>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="text-end" style="width: 80px;">
                                                <a href="{{ route('requests.show', $requestItem) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            /* Modern Kanban Styles */

            /* Smooth Transitions */
            * {
                transition: all 0.2s ease-in-out;
            }

            /* Kanban Container */
            #kanban-container {
                scrollbar-width: thin;
                scrollbar-color: #cbd5e0 #f7fafc;
            }

            #kanban-container::-webkit-scrollbar {
                height: 8px;
            }

            #kanban-container::-webkit-scrollbar-track {
                background: #f7fafc;
                border-radius: 10px;
            }

            #kanban-container::-webkit-scrollbar-thumb {
                background: #cbd5e0;
                border-radius: 10px;
            }

            #kanban-container::-webkit-scrollbar-thumb:hover {
                background: #a0aec0;
            }

            /* Kanban Columns */
            .kanban-column {
                min-width: 320px;
                max-width: 320px;
                border-radius: 12px !important;
                overflow: hidden;
            }

            .kanban-column-body {
                scrollbar-width: thin;
                scrollbar-color: #e2e8f0 transparent;
            }

            .kanban-column-body::-webkit-scrollbar {
                width: 6px;
            }

            .kanban-column-body::-webkit-scrollbar-thumb {
                background: #e2e8f0;
                border-radius: 10px;
            }

            /* Kanban Cards - Modern Design */
            .kanban-card {
                border-radius: 8px !important;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
                cursor: grab;
            }

            .kanban-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
            }

            .kanban-card:active {
                cursor: grabbing;
            }

            /* Time Badge Styles */
            .badge {
                font-weight: 600;
                letter-spacing: 0.3px;
            }

            /* Urgency Classes */
            .kanban-card.urgency-warning {
                border-left-width: 4px !important;
                border-left-color: #f59e0b !important;
                background: linear-gradient(to right, rgba(245, 158, 11, 0.03), transparent);
            }

            .kanban-card.urgency-danger {
                border-left-width: 4px !important;
                border-left-color: #ef4444 !important;
                background: linear-gradient(to right, rgba(239, 68, 68, 0.03), transparent);
            }

            /* Drag States */
            .kanban-ghost-card {
                opacity: 0.4;
                background: #f3f4f6;
            }

            .kanban-dragging {
                opacity: 0.8;
                transform: rotate(3deg);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
            }

            /* Collapsed Column */
            .kanban-closed {
                min-width: 60px !important;
                max-width: 60px !important;
                cursor: pointer;
            }

            .kanban-closed:hover {
                background: #f8f9fa !important;
            }

            .kanban-closed-content {
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100%;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            // Inicializar tooltips do Bootstrap
            document.addEventListener('DOMContentLoaded', function () {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });

            // SortableJS para drag & drop
            document.addEventListener('livewire:initialized', () => {
                let sortables = [];

                const initKanban = () => {
                    sortables.forEach(s => s.destroy());
                    sortables = [];

                    document.querySelectorAll('.kanban-column-body').forEach(column => {
                        sortables.push(new Sortable(column, {
                            group: 'kanban',
                            animation: 200,
                            delay: 100,
                            delayOnTouchOnly: true,
                            draggable: '.kanban-card',
                            ghostClass: 'kanban-ghost-card',
                            dragClass: 'kanban-dragging',
                            easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                            onEnd: evt => {
                                if (evt.from === evt.to) return;
                                @this.handleStatusUpdate(evt.item.dataset.id, evt.to.dataset.status);
                            }
                        }));
                    });
                };

                initKanban();

                Livewire.hook('morph.updated', () => {
                    setTimeout(initKanban, 100);
                });
            });
        </script>
    @endpush