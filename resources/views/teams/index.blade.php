<x-app-layout>
    <x-slot:header>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-custom px-3 py-2">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none"><i class="bi bi-house-door"></i> Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Equipes</li>
            </ol>
        </nav>
    </x-slot:header>

    <div class="container-fluid">
        <div class="bg-white shadow-sm rounded p-4">

            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <h2 class="fw-bold mb-0 fs-3">Gerenciar Equipes</h2>
                    <a href="{{ route('management.teams.create') }}" class="btn btn-primary px-4 rounded-3 ms-2">
                        <i class="bi bi-plus-lg me-1"></i> Nova Equipe
                    </a>
                </div>
            </div>

            <div class="row g-3 align-items-center justify-content-between mb-4">
                {{-- Filtros de Status (Tabs) --}}
                <div class="col-12 col-md-auto">
                    <ul class="nav nav-underline border-bottom-0">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('status') ? 'active fw-bold text-dark' : 'text-muted' }}"
                                href="{{ route('management.teams.index') }}">
                                Todas <span class="small">({{ $teams->total() }})</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'active' ? 'active fw-bold text-dark' : 'text-muted' }}"
                                href="{{ route('management.teams.index', ['status' => 'active']) }}">
                                Ativos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'inactive' ? 'active fw-bold text-dark' : 'text-muted' }}"
                                href="{{ route('management.teams.index', ['status' => 'inactive']) }}">
                                Inativos
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-12 col-md-auto">
                    <form action="{{ route('management.teams.index') }}" method="GET">
                        <div class="input-group bg-white border rounded-3 overflow-hidden">
                            <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search" name="search" value="{{ request('search') }}"
                                class="form-control border-0 shadow-none ps-2" placeholder="Buscar equipe..."
                                style="min-width: 250px;">
                        </div>
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </form>
                </div>
            </div>

            <div class="card border-0 overflow-hidden">
                <div class="card-body p-0">
                    @if($teams->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3 text-muted">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                            <h5 class="fw-bold">Nenhuma Equipe encontrada</h5>
                            @if(request('search') || request('status'))
                                <p class="text-muted">Tente ajustar seus filtros de busca.</p>
                                <a href="{{ route('management.teams.index') }}"
                                    class="btn btn-outline-secondary btn-sm mt-2">Limpar Filtros</a>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom">
                                    <tr class="text-muted small fw-bold">
                                        <th scope="col" class="py-3 ps-4">Nome da Equipe</th>
                                        <th scope="col" class="py-3">Status</th>
                                        <th scope="col" class="py-3 text-end pe-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teams as $team)
                                                                    <tr style="cursor: pointer;"
                                                                        onclick="window.location='{{ route('management.teams.show', $team) }}'">
                                                                        <td class="py-3 ps-4">
                                                                            <div class="fw-bold text-dark">{{ $team->name }}</div>
                                                                        </td>

                                                                        <td class="py-3">
                                                                            @php
                                                                                $color = match (strtolower($team->status)) {
                                                                                    'active' => 'success',
                                                                                    'inactive' => 'danger',
                                                                                    default => 'secondary'
                                                                                };
                                                                                $statusLabel = match (strtolower($team->status)) {
                                                                                    'active' => 'Ativo',
                                                                                    'inactive' => 'Inativo',
                                                                                    default => $team->status
                                                                                };
                                                                            @endphp
                                         <span
                                                                                class="badge rounded-pill bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}">
                                                                                {{ $statusLabel }}
                                                                            </span>
                                                                        </td>

                                                                        <td class="py-3 text-end" onclick="event.stopPropagation();">
                                                                            <div class="d-flex justify-content-end gap-2">
                                                                                <a href="{{ route('management.teams.edit', $team) }}"
                                                                                    class="btn btn-outline-primary btn-sm rounded-2 border-0" title="Editar"
                                                                                    style="background-color: rgba(64, 128, 246, 0.05);">
                                                                                    <i class="bi bi-pencil-fill"></i>
                                                                                </a>

                                                                                <button type="button"
                                                                                    class="btn btn-outline-danger btn-sm rounded-2 border-0" title="Excluir"
                                                                                    style="background-color: rgba(220, 53, 69, 0.05);"
                                                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                                                    data-action="{{ route('management.teams.destroy', $team) }}">
                                                                                    <i class="bi bi-trash-fill"></i>
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($teams->hasPages())
                            <div class="d-flex justify-content-end border-top p-3 bg-light">
                                {{ $teams->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Exclusão --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir esta equipe?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const action = button.getAttribute('data-action');
                    const form = deleteModal.querySelector('#deleteForm');
                    form.setAttribute('action', action);
                });
            }
        </script>
    @endpush
</x-app-layout>