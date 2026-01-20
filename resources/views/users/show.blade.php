<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">Detalhes do Usuário</h2>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            {{-- CABEÇALHO DE CONTEXTO DO USUÁRIO --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-4">
                <div>
                    <h3 class="font-weight-bold mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-1">{{ $user->getRoleName() }}</p>
                    <span class="badge rounded-pill {{ $user->trashed() ? 'bg-danger' : 'bg-success' }}">
                        {{ $user->trashed() ? 'Inativo' : 'Ativo' }}
                    </span>
                </div>
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
                    <button class="nav-link" id="teams-tab" data-bs-toggle="pill" data-bs-target="#teams-tab-pane" type="button" role="tab" aria-controls="teams-tab-pane" aria-selected="false">
                        <i class="bi bi-collection-fill me-1"></i> Equipes ({{ $user->teams->count() }})
                    </button>
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="pill" data-bs-target="#history-tab-pane" type="button" role="tab" aria-controls="history-tab-pane" aria-selected="false">
                        <i class="bi bi-clock-history me-1"></i> Histórico
                    </button>
                </li> --}}
            </ul>

            {{-- CONTEÚDO DAS ABAS --}}
            <div class="tab-content" id="user-details-tabContent">
                
                {{-- Aba 1: Detalhes --}}
                <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">
                    <div class="mt-4">
                        <h5 class="mb-3 font-weight-bold small text-uppercase text-muted">Dados Pessoais</h5>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row border-bottom py-2"><div class="col-md-3"><strong class="text-muted small">Nome Completo</strong></div><div class="col-md-9">{{ $user->name }}</div></div>
                                <div class="row border-bottom py-2"><div class="col-md-3"><strong class="text-muted small">CPF</strong></div><div class="col-md-9">{{ $user->cpf ?? 'Não informado' }}</div></div>
                                <div class="row py-2"><div class="col-md-3"><strong class="text-muted small">Telefone</strong></div><div class="col-md-9">{{ $user->phone ?? 'Não informado' }}</div></div>
                            </div>
                        </div>
                        <h5 class="mb-3 font-weight-bold small text-uppercase text-muted">Dados de Acesso</h5>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row border-bottom py-2"><div class="col-md-3"><strong class="text-muted small">E-mail de Acesso</strong></div><div class="col-md-9">{{ $user->email }}</div></div>
                                <div class="row py-2"><div class="col-md-3"><strong class="text-muted small">Cargo / Função</strong></div><div class="col-md-9">{{ $user->getRoleName() }}</div></div>
                            </div>
                        </div>
                        <h5 class="mb-3 font-weight-bold small text-uppercase text-muted">Endereço</h5>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                @if ($user->postal_code || $user->street)
                                    <div class="row border-bottom py-2"><div class="col-md-3"><strong class="text-muted small">CEP</strong></div><div class="col-md-9">{{ $user->postal_code ?? 'N/A' }}</div></div>
                                    <div class="row border-bottom py-2"><div class="col-md-3"><strong class="text-muted small">Logradouro</strong></div><div class="col-md-9">{{ $user->street ?? 'N/A' }}, {{ $user->number ?? 'S/N' }}</div></div>
                                    <div class="row border-bottom py-2"><div class="col-md-3"><strong class="text-muted small">Bairro</strong></div><div class="col-md-9">{{ $user->neighborhood ?? 'N/A' }}</div></div>
                                    <div class="row border-bottom py-2"><div class="col-md-3"><strong class="text-muted small">Cidade / UF</strong></div><div class="col-md-9">{{ $user->city ?? 'N/A' }} - {{ $user->state ?? 'N/A' }}</div></div>
                                    <div class="row py-2"><div class="col-md-3"><strong class="text-muted small">Complemento</strong></div><div class="col-md-9">{{ $user->complement ?? 'Nenhum' }}</div></div>
                                @else
                                    <p class="text-muted mb-0">Nenhum endereço cadastrado para este usuário.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="teams-tab-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#attachTeamModal">
                            <i class="bi bi-plus-circle me-1"></i> Adicionar a Nova Equipe
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="border-bottom"><tr class="text-muted small">
                                <th class="py-3">EQUIPE</th>
                                <th class="py-3 text-center">STATUS</th>
                                <th class="py-3">ADICIONADO EM</th>
                                <th class="py-3 text-end">AÇÕES</th>
                            </tr></thead>
                            <tbody>
                                @forelse ($user->teams as $team)
                                    <tr>
                                        <td><a href="{{ route('management.teams.show', $team) }}" class="fw-bold text-decoration-none text-dark">{{ $team->name }}</a></td>
                                        <td class="text-center"><span class="badge rounded-pill {{ $team->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ $team->status === 'active' ? 'Ativa' : 'Inativa' }}</span></td>
                                        <td>{{ $team->pivot->created_at->format('d/m/Y') }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" data-bs-target="#detachTeamModal" 
                                                    data-action="{{ route('management.users.teams.detach', ['user' => $user, 'team' => $team]) }}"
                                                    data-team-name="{{ $team->name }}"
                                                    title="Desvincular da equipe">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">Este usuário não pertence a nenhuma equipe.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="attachTeamModal" tabindex="-1" aria-labelledby="attachTeamModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('management.users.teams.attach', $user) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="attachTeamModalLabel">Adicionar a Novas Equipes</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if($availableTeams->isNotEmpty())
                                        <p>Selecione uma ou mais equipes para adicionar <strong>{{ $user->name }}</strong>.</p>
                                        <select id="select-teams-attach" name="teams[]" multiple placeholder="Selecione as equipes...">
                                            @foreach ($availableTeams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <p class="text-muted text-center">Não há outras equipes disponíveis para adicionar este usuário.</p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    @if($availableTeams->isNotEmpty())
                                        <button type="submit" class="btn btn-primary">Adicionar</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal para REMOVER de uma equipe --}}
                <div class="modal fade" id="detachTeamModal" tabindex="-1" aria-labelledby="detachTeamModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header"><h5 class="modal-title" id="detachTeamModalLabel">Confirmar Remoção</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                            <div class="modal-body">Tem certeza que deseja desvincular {{ $user->name }} desta equipe?</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <form id="detachTeamForm" method="POST" action="">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Sim, Desvincular</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aba 3: Histórico (Funcional) --}}
                <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" aria-labelledby="history-tab" tabindex="0">
                    <div class="mt-4">
                        <ul class="list-group">
                            @forelse ($user->activityLogs()->latest()->get() as $log)
                                <li class="list-group-item">
                                    <div>
                                        <strong class="fw-bold text-capitalize">{{ class_basename($log->subject_type) }} {{ $log->description }}</strong>
                                    </div>
                                    <small class="text-muted">
                                        Feito por <strong>{{ $log->causer_name }}</strong> em {{ $log->created_at->format('d/m/Y \à\s H:i') }}
                                    </small>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">
                                    Nenhuma atividade registrada para este usuário.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>
            
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // ✅ INÍCIO DA CORREÇÃO
                // Primeiro, encontra o elemento do seletor.
                const attachSelect = document.getElementById('select-teams-attach');
                
                // Apenas se o elemento existir na página...
                if (attachSelect) {
                    // ...inicializa o TomSelect.
                    new TomSelect(attachSelect, { plugins: ['remove_button'] });
                }
                // ✅ FIM DA CORREÇÃO

                // Lógica para o modal de remover equipe (que agora será executada sem erros)
                const detachTeamModal = document.getElementById('detachTeamModal');
                if (detachTeamModal) {
                    detachTeamModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        const action = button.getAttribute('data-action');
                        const teamName = button.getAttribute('data-team-name');
                        
                        detachTeamModal.querySelector('.modal-body').textContent = `Tem certeza que deseja desvincular {{ $user->name }} da equipe "${teamName}"?`;
                        detachTeamModal.querySelector('#detachTeamForm').setAttribute('action', action);
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>