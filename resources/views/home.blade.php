<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Página Inicial
        </h2>
    </x-slot>

    <x-ui.flash-message />

    {{-- Mensagem de Boas-vindas --}}
    <div class="mb-4">
        <h3>Olá, {{ $user->name }}! 👋</h3>
        <p class="text-muted">
            Bem-vindo(a) de volta.</strong>
        </p>
    </div>

    {{-- Seção de Resumo Rápido (Stats) --}}
    <div class="row mb-4">
        {{-- Meus Chamados Abertos --}}
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card text-bg-light shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-primary me-3">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">{{ $stats['myOpenRequestsCount'] }}</h5>
                            <p class="card-text small text-muted">Meus Chamados Abertos/Em Andamento</p>
                            <a href="{{ route('requests.index') }}" class="stretched-link small">Ver detalhes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chamados Pendentes na Área (para Staff/Manager/Admin) --}}
        @if ($user->role === 'admin' || in_array($user->role, ['manager', 'staff']))
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card text-bg-light shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                         <div class="fs-1 {{ $stats['pendingAreaRequestsCount'] > 0 ? 'text-warning' : 'text-secondary' }} me-3">
                             <i class="bi bi-inbox-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">{{ $stats['pendingAreaRequestsCount'] }}</h5>
                            <p class="card-text small text-muted">Chamados Aguardando Atribuição na Fila</p>
                             <a href="{{ route('requests.index') }}" class="stretched-link small">Ver fila</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Exemplo: PDVs Inativos (para Gestão) --}}
        @if ($user->role === 'admin' || $user->role === 'manager')
        <div class="col-md-6 col-lg-4 mb-3">
             <div class="card text-bg-light shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="fs-1 text-danger me-3">
                            <i class="bi bi-shop-window"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">{{ $stats['inactivePdvsCount'] }}</h5>
                            <p class="card-text small text-muted">PDVs Inativos ou Fechados</p>
                            <a href="{{ route('pdvs.index') }}" class="stretched-link small">Ver PDVs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Seção de Ações Rápidas --}}
    <h4 class="mb-3">Ações Rápidas</h4>
    <div class="row">
        {{-- Abrir Chamado --}}
        <div class="col-md-6 col-lg-4 mb-3">
            <a href="{{ route('requests.create') }}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm card-hover">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-plus-circle-dotted display-4 text-success mb-3"></i>
                        <h5 class="card-title">Abrir Chamado</h5>
                        <p class="card-text small text-muted">Precisa de ajuda ou quer solicitar algo?</p>
                    </div>
                </div>
            </a>
        </div>

         {{-- Gerenciar PDVs (Staff+) --}}
        @if ($user->role === 'admin' || in_array($user->role, ['manager', 'staff']))
        <div class="col-md-6 col-lg-4 mb-3">
            <a href="{{ route('pdvs.index') }}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm card-hover">
                     <div class="card-body text-center p-4">
                         <i class="bi bi-shop display-4 text-primary mb-3"></i>
                        <h5 class="card-title">Pontos de Venda</h5>
                        <p class="card-text small text-muted">Gerenciar locais e status dos PDVs.</p>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Gerenciar Usuários (Manager+) --}}
        @if ($user->role === 'admin' || $user->role === 'manager')
        <div class="col-md-6 col-lg-4 mb-3">
            <a href="{{ route('management.users.index') }}" class="text-decoration-none text-dark">
                <div class="card h-100 shadow-sm card-hover">
                     <div class="card-body text-center p-4">
                        <i class="bi bi-person-lines-fill display-4 text-info mb-3"></i>
                        <h5 class="card-title">Gerenciar Usuários</h5>
                        <p class="card-text small text-muted">Adicionar, editar e definir cargos.</p>
                    </div>
                </div>
            </a>
        </div>
        @endif

         {{-- Gerenciar Equipes (Manager+) --}}
         @if ($user->role === 'admin' || $user->role === 'manager')
        <div class="col-md-6 col-lg-4 mb-3">
             <a href="{{ route('management.teams.index') }}" class="text-decoration-none text-dark">
                 <div class="card h-100 shadow-sm card-hover">
                    <div class="card-body text-center p-4">
                         <i class="bi bi-people-fill display-4 text-info mb-3"></i>
                         <h5 class="card-title">Gerenciar Equipes</h5>
                         <p class="card-text small text-muted">Organizar usuários em equipes.</p>
                    </div>
                </div>
            </a>
        </div>
         @endif

        {{-- Gerenciar Áreas (Manager+) --}}
         @if ($user->role === 'admin' || $user->role === 'manager')
        <div class="col-md-6 col-lg-4 mb-3">
             <a href="{{ route('areas.index') }}" class="text-decoration-none text-dark">
                 <div class="card h-100 shadow-sm card-hover">
                    <div class="card-body text-center p-4">
                         <i class="bi bi-collection-fill display-4 text-info mb-3"></i>
                         <h5 class="card-title">Gerenciar Áreas</h5>
                         <p class="card-text small text-muted">Definir as áreas organizacionais.</p>
                    </div>
                </div>
            </a>
        </div>
        @endif

        {{-- Outros cartões que você tinha podem ser adicionados aqui se relevantes --}}

    </div>

</x-app-layout>