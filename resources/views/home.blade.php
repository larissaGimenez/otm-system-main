<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Página Inicial
        </h2>
    </x-slot>

    <x-ui.flash-message />

    {{-- 1. Mensagem de Boas-vindas (Estilo "E-commerce Dashboard") --}}
    <div class="mb-4">
        <h3 class="fw-bold">Olá, {{ $user->name }}! 👋</h3>
        <p class="text-muted">
            Veja o que está acontecendo no seu painel.
        </p>
    </div>

    {{-- ========================================================== --}}
    {{-- ============ 2. STATS PRINCIPAIS (FOCO NO USUÁRIO) ========= --}}
    {{-- ========================================================== --}}
    <h5 class="mb-3 fw-bold">Suas Métricas</h5>
    <div class="row g-3 mb-4">
        
        {{-- Card: Minha Fila de Atendimento --}}
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-primary me-3">
                            <i class="bi bi-person-workspace"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $myAssignedRequests->count() }}</h4>
                            <p class="card-text small text-muted mb-0">Na sua Fila de Atendimento</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Meus Chamados Solicitados --}}
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-success me-3">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $stats['myOpenRequestsCount'] }}</h4>
                            <p class="card-text small text-muted mb-0">Meus Chamados Solicitados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Fila da Área (Staff/Manager/Admin) --}}
        @if ($user->role === 'admin' || in_array($user->role, ['manager', 'staff']))
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 {{ $stats['pendingAreaRequestsCount'] > 0 ? 'text-warning' : 'text-secondary' }} me-3">
                            <i class="bi bi-inbox-fill"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">{{ $stats['pendingAreaRequestsCount'] }}</h4>
                            <p class="card-text small text-muted mb-0">Aguardando na Fila da Área</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ========================================================== --}}
    {{-- ============ 3. FILA DE ATENDIMENTO (TABELA) ============= --}}
    {{-- ========================================================== --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="mb-0 fw-bold">Minha Fila de Atendimento</h5>
        </div>

        @if ($myAssignedRequests->isEmpty())
            <div class="card-body text-center p-5">
                <i class="bi bi-check2-circle display-4 text-success mb-3"></i>
                <h5 class="mt-2">Tudo certo por aqui!</h5>
                <p class="text-muted">Você não possui nenhum chamado atribuído a você no momento.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-muted">
                        <tr>
                            <th scope="col" class="py-3 px-3">Título</th>
                            <th scope="col" class="py-3">Área</th>
                            <th scope="col" class="py-3">Solicitante</th>
                            <th scope="col" class="py-3">Prioridade</th>
                            <th scope="col" class="py-3">Atualizado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($myAssignedRequests as $request)
                            <tr style="cursor: pointer;" onclick="window.location='{{ route('requests.show', $request) }}';">
                                <td class="py-3 px-3 fw-bold">{{ $request->title }}</td>
                                <td class="py-3">{{ $request->area->name ?? 'N/A' }}</td>
                                <td class="py-3">{{ $request->requester->name ?? 'N/A' }}</td>
                                <td class="py-3">
                                    <span class="badge rounded-pill bg-{{ $request->priority->colors() }}">
                                        {{ $request->priority->getLabel() }}
                                    </span>
                                </td>
                                <td class="py-3 small text-muted">{{ $request->updated_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($myAssignedRequests->count() >= 10)
            <div class="card-footer bg-white text-center py-2 border-top-0">
                <a href="{{ route('requests.index') }}" class="small text-muted text-decoration-none">
                    Ver todos...
                </a>
            </div>
            @endif
        @endif
    </div>

    {{-- ========================================================== --}}
    {{-- ============ 4. VISÃO GERAL (ADMIN/MANAGER) ============== --}}
    {{-- ========================================================== --}}
    @if ($user->role === 'admin' || $user->role === 'manager')
        <hr class="my-4">
        <h5 class="mb-3 fw-bold">Visão Geral de PDV's</h5>
        <div class="row g-3">
            {{-- PDVs Inativos (para Gestão) --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="fs-1 text-danger me-3"><i class="bi bi-shop-window"></i></div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $stats['inactivePdvsCount'] }}</h4>
                                <p class="card-text small text-muted mb-0">PDVs Inativos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total de Chamados Abertos (Apenas Admin) --}}
            @if ($user->role === 'admin' && isset($stats['totalOpenRequests']))
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="fs-1 text-info me-3"><i class="bi bi-broadcast"></i></div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $stats['totalOpenRequests'] }}</h4>
                                <p class="card-text small text-muted mb-0">Abertos Agora</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Chamados Abertos este Mês (Apenas Admin) --}}
            @if ($user->role === 'admin' && isset($stats['totalRequestsThisMonth']))
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="fs-1 text-success me-3"><i class="bi bi-calendar-plus"></i></div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $stats['totalRequestsThisMonth'] }}</h4>
                                <p class="card-text small text-muted mb-0">Abertos no Mês</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Total de Chamados Histórico (Apenas Admin) --}}
            @if ($user->role === 'admin' && isset($stats['totalRequestsAllTime']))
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="fs-1 text-secondary me-3"><i class="bi bi-bar-chart-line-fill"></i></div>
                            <div>
                                <h4 class="fw-bold mb-0">{{ $stats['totalRequestsAllTime'] }}</h4>
                                <p class="card-text small text-muted mb-0">Total (Histórico)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @endif
    
</x-app-layout>