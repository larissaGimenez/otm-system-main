<x-app-layout>

    {{-- Define o cabeçalho da página --}}
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Página Inicial') }}
        </h2>
    </x-slot>

    {{-- Mensagem de boas-vindas personalizada --}}
    <div class="mb-4">
        <h3>Olá, {{ Auth::user()->name }}!</h3>
        <p class="text-muted">
            Seu cargo é: <strong>{{ Auth::user()->getRoleName() }}</strong>. Selecione uma das opções abaixo para começar.
        </p>
    </div>

    {{-- Grid com os cartões de ação rápida --}}
    <div class="row">

        {{-- Cartão: Migração de Clientes (Visível para Equipe Interna) --}}
        @if (Auth::user()->hasAnyRole(['admin', 'manager', 'staff']))
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="{{ route('migracao.clientes.index') }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm card-hover">
                        <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                            <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Migrar Clientes</h5>
                            <p class="card-text small text-muted">Sincronize os dados de clientes do sistema antigo.</p>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        {{-- Cartão: Configurações (Visível para Equipe Interna) --}}
        @if (Auth::user()->hasAnyRole(['admin', 'manager', 'staff']))
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="{{ route('configuracoes.index') }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm card-hover">
                        <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                            <i class="bi bi-gear-fill display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Configurações</h5>
                            <p class="card-text small text-muted">Ajuste os parâmetros gerais do sistema.</p>
                        </div>
                    </div>
                </a>
            </div>
        @endif
        
        {{-- Cartão: Gerenciar Usuários (Visível para Gestores) --}}
        @if (Auth::user()->hasAnyRole(['admin', 'manager']))
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="{{ route('management.users.index') }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm card-hover">
                        <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                            <i class="bi bi-person-lines-fill display-4 text-primary mb-3"></i>
                            <h5 class="card-title">Gerenciar Usuários</h5>
                            <p class="card-text small text-muted">Adicione, edite e defina cargos para os usuários.</p>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        {{-- Cartão: Painel Admin (Visível apenas para Administradores) --}}
        @if (Auth::user()->isAdmin())
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm card-hover">
                        <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                            <i class="bi bi-shield-lock-fill display-4 text-danger mb-3"></i>
                            <h5 class="card-title">Painel Admin</h5>
                            <p class="card-text small text-muted">Acesse a área de administração do sistema.</p>
                        </div>
                    </div>
                </a>
            </div>
        @endif

    </div>

</x-app-layout>