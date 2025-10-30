<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Chamado: {{ $request->title }}
        </h2>
    </x-slot>

    <x-ui.flash-message />

    <div class="row">
        {{-- Coluna Principal (Detalhes) --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">Descrição</h5>
                    
                    @if($request->description)
                        <div class="text-muted" style="white-space: pre-wrap;">{!! nl2br(e($request->description)) !!}</div>
                    @else
                        <p class="text-muted fst-italic">Nenhuma descrição fornecida.</p>
                    @endif

                    @if ($request->attachment_path)
                        <hr>
                        <h5 class="card-title border-bottom pb-2 mb-3">Anexo</h5>
                        <p>
                            <i class="bi bi-paperclip"></i>
                            <a href="{{ Storage::url($request->attachment_path) }}" target="_blank">
                                {{ $request->attachment_original_name ?? 'Baixar Anexo' }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Barra Lateral (Informações e Ações) --}}
        <div class="col-lg-4">
            
            {{-- Card de Detalhes --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Detalhes</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Status:
                            <span class="badge bg-primary rounded-pill">{{ $request->status->getLabel() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Prioridade:
                            <strong class="badge rounded-pill bg-{{ $request->priority->colors() }}">
                                {{ $request->priority->getLabel() }}
                            </strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Tipo:
                            <strong>{{ $request->type->getLabel() }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Área:
                            <strong>{{ $request->area->name }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Solicitante:
                            <strong>{{ $request->requester->name }}</strong>
                        </li>
                        @if($request->due_at)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Prazo:
                            <strong class="text-danger">{{ $request->due_at->format('d/m/Y') }}</strong>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Criado em:
                            <small>{{ $request->created_at->format('d/m/Y \à\s H:i') }}</small>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            Atualizado em:
                            <small>{{ $request->updated_at->format('d/m/Y \à\s H:i') }}</small>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Card de Responsáveis --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Responsáveis</h5>
                    
                    @forelse ($request->assignees as $assignee)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $assignee->name }}</span>
                            <form action="{{ route('requests.assignees.detach', [$request, $assignee]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover" style="--bs-btn-padding-y: .1rem; --bs-btn-padding-x: .35rem; --bs-btn-font-size: .75rem;">
                                    &times;
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-muted small">Nenhum responsável atribuído.</p>
                    @endforelse
                    
                    <hr>
                    
                    @php
                        $filteredAssignees = $availableAssignees->whereNotIn('id', $request->assignees->pluck('id'));
                    @endphp
                    
                    @if($filteredAssignees->isNotEmpty())
                        <form action="{{ route('requests.assignees.attach', $request) }}" method="POST">
                            @csrf
                            <label for="assignees" class="form-label small">Atribuir novo:</label>
                            
                            {{-- 
                                CORREÇÃO: 
                                O <select multiple> foi removido do .input-group, pois o Bootstrap 5 não
                                suporta 'select multiple' dentro de 'input-group'.
                                Adicionamos 'size="5"' para melhorar a aparência.
                            --}}
                            <div class="mb-2">
                                <select class="form-select" id="assignees" name="assignees[]" multiple required size="5">
                                    @foreach ($filteredAssignees as $user)
                                        <option value="{{ $user->id }}"
                                            {{ in_array($user->id, old('assignees', [])) ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary" type="submit">Atribuir</button>
                            </div>
                            
                            @error('assignees') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            @error('assignees.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </form>
                    @else
                         <p class="text-muted small">Todos os usuários da área já estão atribuídos.</p>
                    @endif
                </div>
            </div>

            {{-- Card de Ações --}}
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Ações</h5>
                    <div class="d-grid gap-2">
                        
                        {{-- ADICIONAR ESTE @can --}}
                        @can('update', $request)
                        <a href="{{ route('requests.edit', $request) }}" class="btn btn-outline-primary">
                            Editar Chamado
                        </a>
                        @endcan
                        
                        {{-- ADICIONAR ESTE @can --}}
                        @can('delete', $request)
                        <form action="{{ route('requests.destroy', $request) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este chamado?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                Excluir Chamado
                            </button>
                        </form>
                        @endcan

                    </div>
                </div>
            </div>
</x-app-layout>