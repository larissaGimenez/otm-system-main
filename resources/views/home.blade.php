<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold ms-2">Início</h2>
    </x-slot>

    <x-ui.flash-message />

    {{-- Saudação simples --}}
    <div class="mb-4 ms-2">
        <h4 class="fw-bold">Olá, {{ $user->name }} 👋</h4>
        <p class="text-muted small mb-0">Resumo rápido do que importa agora.</p>
    </div>

    {{-- Métricas principais --}}
    <div class="row g-3 mb-4">

        {{-- Minha fila --}}
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="fs-2 text-primary me-3"><i class="bi bi-person-workspace"></i></div>
                    <div>
                        <div class="h4 fw-bold mb-0">{{ $myAssignedRequests->count() }}</div>
                        <span class="text-muted small">Minha fila</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Meus chamados --}}
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="fs-2 text-success me-3"><i class="bi bi-journal-text"></i></div>
                    <div>
                        <div class="h4 fw-bold mb-0">{{ $stats['myOpenRequestsCount'] }}</div>
                        <span class="text-muted small">Criados por mim</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fila da área (staff/manager/admin) --}}
        @if(in_array($user->role, ['admin','manager','staff']))
        <div class="col-12 col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="fs-2 {{ $stats['pendingAreaRequestsCount'] ? 'text-warning':'text-secondary' }} me-3">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                    <div>
                        <div class="h4 fw-bold mb-0">{{ $stats['pendingAreaRequestsCount'] }}</div>
                        <span class="text-muted small">Fila da área</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Minha fila detalhada --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="mb-0 fw-bold">Minha Fila</h5>
        </div>

        @if($myAssignedRequests->isEmpty())
            <div class="card-body text-center p-5">
                <i class="bi bi-check2-circle display-5 text-success mb-3"></i>
                <p class="fw-semibold mb-0">Nenhum chamado pendente</p>
                <span class="text-muted small">Aproveite para respirar ✨</span>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                        <tr>
                            <th class="py-3 px-3">Título</th>
                            <th>Área</th>
                            <th>Solicitante</th>
                            <th>Prioridade</th>
                            <th>Atualização</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($myAssignedRequests as $request)
                        <tr style="cursor:pointer" onclick="window.location='{{ route('requests.show', $request) }}'">
                            <td class="fw-bold px-3">{{ $request->title }}</td>
                            <td>{{ $request->area->name ?? '—' }}</td>
                            <td>{{ $request->requester->name ?? '—' }}</td>
                            <td>
                                <span class="badge rounded-pill bg-{{ $request->priority->colors() }}">
                                    {{ $request->priority->getLabel() }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $request->updated_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($myAssignedRequests->count() >= 10)
            <div class="card-footer bg-white text-center py-2">
                <a href="{{ route('requests.index') }}" class="small text-muted text-decoration-none">
                    Ver todos
                </a>
            </div>
            @endif
        @endif
    </div>

    {{-- Visão geral (bem reduzida) --}}
    @if(in_array($user->role, ['admin','manager']))
    <h5 class="fw-bold mb-3">Visão Geral</h5>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="fs-2 text-danger mb-2"><i class="bi bi-shop-window"></i></div>
                    <div class="h5 fw-bold mb-0">{{ $stats['inactivePdvsCount'] }}</div>
                    <span class="text-muted small">PDVs Inativos</span>
                </div>
            </div>
        </div>

        @if($user->role === 'admin')
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="fs-2 text-info mb-2"><i class="bi bi-broadcast"></i></div>
                    <div class="h5 fw-bold mb-0">{{ $stats['totalOpenRequests'] }}</div>
                    <span class="text-muted small">Chamados Abertos</span>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

</x-app-layout>
