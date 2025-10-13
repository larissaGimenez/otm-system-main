<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Detalhes do Usuário
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            {{-- CABEÇALHO DE CONTEXTO DO USUÁRIO --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-4">
                {{-- Informações Principais --}}
                <div>
                    <h3 class="font-weight-bold mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-1">{{ $user->getRoleName() }}</p>
                    <span class="badge rounded-pill {{ $user->trashed() ? 'bg-danger' : 'bg-success' }}">
                        {{ $user->trashed() ? 'Inativo' : 'Ativo' }}
                    </span>
                </div>
                {{-- Botões de Ação Principais --}}
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('management.users.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="bi bi-arrow-left me-1"></i> Voltar para a Lista
                    </a>
                    <a href="{{ route('management.users.edit', $user) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-fill me-1"></i> Editar Usuário
                    </a>
                </div>
            </div>

            <hr>

            {{-- NAVEGAÇÃO DAS ABAS --}}
            <ul class="nav nav-pills mb-3" id="user-details-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="pill" data-bs-target="#details-tab-pane" type="button" role="tab" aria-controls="details-tab-pane" aria-selected="true">
                        <i class="bi bi-person-vcard-fill me-1"></i> Detalhes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="groups-tab" data-bs-toggle="pill" data-bs-target="#groups-tab-pane" type="button" role="tab" aria-controls="groups-tab-pane" aria-selected="false">
                        <i class="bi bi-collection-fill me-1"></i> Grupos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activities-tab" data-bs-toggle="pill" data-bs-target="#activities-tab-pane" type="button" role="tab" aria-controls="activities-tab-pane" aria-selected="false">
                        <i class="bi bi-activity me-1"></i> Atividades
                    </button>
                </li>
            </ul>

            {{-- CONTEÚDO DAS ABAS --}}
            <div class="tab-content" id="user-details-tabContent">
                
                {{-- Em resources/views/users/show.blade.php --}}

                {{-- Aba 1: Detalhes (Versão Refatorada e Organizada) --}}
                <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">
                    <div class="mt-4">

                        {{-- SEÇÃO: DADOS PESSOAIS --}}
                        <h5 class="mb-3 font-weight-bold small text-uppercase text-muted">Dados Pessoais</h5>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row border-bottom py-2">
                                    <div class="col-md-3"><strong class="text-muted small">Nome Completo</strong></div>
                                    <div class="col-md-9">{{ $user->name }}</div>
                                </div>
                                <div class="row border-bottom py-2">
                                    <div class="col-md-3"><strong class="text-muted small">CPF</strong></div>
                                    <div class="col-md-9">{{ $user->cpf ?? 'Não informado' }}</div>
                                </div>
                                <div class="row py-2">
                                    <div class="col-md-3"><strong class="text-muted small">Telefone</strong></div>
                                    <div class="col-md-9">{{ $user->phone ?? 'Não informado' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- SEÇÃO: DADOS DE ACESSO --}}
                        <h5 class="mb-3 font-weight-bold small text-uppercase text-muted">Dados de Acesso</h5>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row border-bottom py-2">
                                    <div class="col-md-3"><strong class="text-muted small">E-mail de Acesso</strong></div>
                                    <div class="col-md-9">{{ $user->email }}</div>
                                </div>
                                <div class="row py-2">
                                    <div class="col-md-3"><strong class="text-muted small">Cargo / Função</strong></div>
                                    <div class="col-md-9">{{ $user->getRoleName() }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- SEÇÃO: ENDEREÇO --}}
                        <h5 class="mb-3 font-weight-bold small text-uppercase text-muted">Endereço</h5>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                @if ($user->postal_code || $user->street)
                                    <div class="row border-bottom py-2">
                                        <div class="col-md-3"><strong class="text-muted small">CEP</strong></div>
                                        <div class="col-md-9">{{ $user->postal_code ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row border-bottom py-2">
                                        <div class="col-md-3"><strong class="text-muted small">Logradouro</strong></div>
                                        <div class="col-md-9">{{ $user->street ?? 'N/A' }}, {{ $user->number ?? 'S/N' }}</div>
                                    </div>
                                    <div class="row border-bottom py-2">
                                        <div class="col-md-3"><strong class="text-muted small">Bairro</strong></div>
                                        <div class="col-md-9">{{ $user->neighborhood ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row border-bottom py-2">
                                        <div class="col-md-3"><strong class="text-muted small">Cidade / UF</strong></div>
                                        <div class="col-md-9">{{ $user->city ?? 'N/A' }} - {{ $user->state ?? 'N/A' }}</div>
                                    </div>
                                    <div class="row py-2">
                                        <div class="col-md-3"><strong class="text-muted small">Complemento</strong></div>
                                        <div class="col-md-9">{{ $user->complement ?? 'Nenhum' }}</div>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">Nenhum endereço cadastrado para este usuário.</p>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                </div>

                {{-- Aba 2: Grupos (Placeholder) --}}
                <div class="tab-pane fade" id="groups-tab-pane" role="tabpanel" aria-labelledby="groups-tab" tabindex="0">
                    <div class="text-center py-5">
                        <i class="bi bi-collection display-1 text-muted"></i>
                        <h4 class="mt-3">Grupos do Usuário</h4>
                        <p class="text-muted">Este usuário ainda não está associado a nenhum grupo.</p>
                        {{-- <a href="#" class="btn btn-primary btn-sm">Associar a um Grupo</a> --}}
                    </div>
                </div>

                {{-- Aba 3: Atividades (Placeholder) --}}
                <div class="tab-pane fade" id="activities-tab-pane" role="tabpanel" aria-labelledby="activities-tab" tabindex="0">
                    <div class="text-center py-5">
                        <i class="bi bi-activity display-1 text-muted"></i>
                        <h4 class="mt-3">Log de Atividades</h4>
                        <p class="text-muted">Nenhuma atividade recente registrada para este usuário.</p>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</x-app-layout>