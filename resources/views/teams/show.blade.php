<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Detalhes da Equipe
        </h2>
    </x-slot>

    {{-- Exibe mensagens de sucesso/erro --}}
    @if(session('success'))<div class="alert alert-success mb-4">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger mb-4">{{ session('error') }}</div>@endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            {{-- CABEÇALHO DE CONTEXTO DA EQUIPE --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-4">
                <div>
                    <h3 class="font-weight-bold mb-1">{{ $team->name }}</h3>
                    <p class="text-muted mb-2">
                        <span class="badge rounded-pill {{ $team->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ $team->status === 'active' ? 'Ativa' : 'Inativa' }}
                        </span>
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('management.teams.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="bi bi-arrow-left me-1"></i> Voltar
                    </a>
                    <a href="{{ route('management.teams.edit', $team) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-fill me-1"></i> Editar Equipe
                    </a>
                </div>
            </div>

            <hr>

            {{-- NAVEGAÇÃO DAS ABAS --}}
            <ul class="nav nav-pills mb-3" id="team-details-tab" role="tablist">
                <li class="nav-item" role="presentation"><button class="nav-link active" id="details-tab" data-bs-toggle="pill" data-bs-target="#details-tab-pane" type="button" role="tab"><i class="bi bi-info-circle-fill me-1"></i> Detalhes</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="members-tab" data-bs-toggle="pill" data-bs-target="#members-tab-pane" type="button" role="tab"><i class="bi bi-people-fill me-1"></i> Membros ({{ $team->users->count() }})</button></li>
                <li class="nav-item" role="presentation"><button class="nav-link" id="history-tab" data-bs-toggle="pill" data-bs-target="#history-tab-pane" type="button" role="tab"><i class="bi bi-clock-history me-1"></i> Histórico</button></li>
            </ul>

            {{-- CONTEÚDO DAS ABAS --}}
            <div class="tab-content" id="team-details-tabContent">
                
                {{-- Aba 1: Detalhes --}}
                <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel" tabindex="0">
                    <div class="mt-4 card border-0 shadow-sm"><div class="card-body">
                        <div class="row border-bottom py-2"><div class="col-md-3"><strong class="text-muted small">DESCRIÇÃO</strong></div><div class="col-md-9">{{ $team->description ?? 'Nenhuma descrição fornecida.' }}</div></div>
                        <div class="row py-2"><div class="col-md-3"><strong class="text-muted small">CRIADA EM</strong></div><div class="col-md-9">{{ $team->created_at->format('d/m/Y \à\s H:i') }}</div></div>
                    </div></div>
                </div>

                {{-- Aba 2: Membros da Equipe --}}
                <div class="tab-pane fade" id="members-tab-pane" role="tabpanel" tabindex="0">
                    <div class="d-flex justify-content-end mt-4 mb-3">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                            <i class="bi bi-person-plus-fill me-1"></i> Adicionar Membro
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="border-bottom"><tr class="text-muted small">
                                <th class="py-3">MEMBRO</th><th class="py-3">E-MAIL</th><th class="py-3 text-center">CARGO</th><th class="py-3">ADICIONADO EM</th><th class="py-3 text-end">AÇÕES</th>
                            </tr></thead>
                            <tbody>
                                @forelse ($team->users as $user)
                                    <tr>
                                        <td><a href="{{ route('management.users.show', $user) }}" class="text-decoration-none text-dark fw-bold">{{ $user->name }}</a></td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center"><span class="badge bg-primary rounded-pill">{{ $user->getRoleName() }}</span></td>
                                        <td>{{ $user->pivot->created_at->format('d/m/Y') }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#removeMemberModal" data-action="{{ route('management.teams.removeUser', ['team' => $team, 'user' => $user]) }}" data-member-name="{{ $user->name }}" title="Remover da equipe"><i class="bi bi-person-dash-fill"></i></button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">Nenhum membro associado a esta equipe.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Aba 3: Histórico --}}
                <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" tabindex="0">
                    <div class="mt-4"><ul class="list-group">
                        @forelse ($team->activityLogs()->latest()->get() as $log)
                            <li class="list-group-item">
                                <div><strong class="fw-bold text-capitalize">{{ class_basename($log->subject_type) }} {{ $log->description }}</strong></div>
                                <small class="text-muted">Feito por <strong>{{ $log->causer_name }}</strong> em {{ $log->created_at->format('d/m/Y \à\s H:i') }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Nenhuma atividade registrada para esta equipe.</li>
                        @endforelse
                    </ul></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para ADICIONAR membros --}}
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('management.teams.attachUsers', $team) }}" method="POST">
                    @csrf
                    <div class="modal-header"><h5 class="modal-title" id="addMemberModalLabel">Adicionar Membros à Equipe</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        @if($availableUsers->isNotEmpty())
                            <p>Selecione um ou mais usuários para adicionar a <strong>{{ $team->name }}</strong>.</p>
                            <select id="select-users-attach" name="users[]" multiple placeholder="Selecione os usuários...">
                                @foreach ($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        @else
                            <p class="text-muted text-center">Todos os usuários já estão nesta equipe.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        @if($availableUsers->isNotEmpty())
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal para REMOVER membro --}}
    <div class="modal fade" id="removeMemberModal" tabindex="-1" aria-labelledby="removeMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="removeMemberModalLabel">Remover Membro</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="removeMemberForm" method="POST" action="">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Sim, Remover</button></form>
            </div>
        </div></div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializa o TomSelect para o modal de adicionar membros
            if (document.getElementById('select-users-attach')) {
                new TomSelect('#select-users-attach', { plugins: ['remove_button'] });
            }

            // Lógica para o modal de remover membro
            const removeMemberModal = document.getElementById('removeMemberModal');
            if (removeMemberModal) {
                removeMemberModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const action = button.getAttribute('data-action');
                    const memberName = button.getAttribute('data-member-name');
                    removeMemberModal.querySelector('.modal-body').textContent = `Tem certeza que deseja remover "${memberName}" desta equipe?`;
                    removeMemberModal.querySelector('#removeMemberForm').setAttribute('action', action);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>