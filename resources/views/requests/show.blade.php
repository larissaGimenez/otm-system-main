<x-app-layout>

    <x-ui.flash-message />

    <x-slot name="header">
        <div class="container-fluid px-0">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Detalhes do Chamado
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Barra de Ações --}}
    <div class="container-fluid mb-3">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <div class="me-auto">
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem;">Ações
                            Rápidas</small>
                    </div>

                    @can('update', $request)
                        {{-- Botão Iniciar Atendimento - apenas quando Em Aberto --}}
                        @if($request->status === \App\Enums\Request\RequestStatus::OPEN)
                            <form action="{{ route('requests.start', $request) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
                                    <i class="bi bi-play-circle-fill"></i>
                                    <span>Iniciar Atendimento</span>
                                </button>
                            </form>
                        @endif

                        {{-- Botão Fechar Chamado - quando Em Andamento ou Solução Longa --}}
                        @if(in_array($request->status, [\App\Enums\Request\RequestStatus::IN_PROGRESS, \App\Enums\Request\RequestStatus::LONG_SOLUTION]))
                            <a href="{{ route('requests.close-form', $request) }}"
                                class="btn btn-success btn-sm d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Fechar Chamado</span>
                            </a>
                        @endif

                        {{-- Indicador de Status Atual --}}
                        <div class="vr d-none d-md-block"></div>
                        <div class="d-flex align-items-center gap-2">
                            <small class="text-muted">Status:</small>
                            <span class="badge bg-{{ $request->status->colors() }} rounded-pill">
                                {{ $request->status->getLabel() }}
                            </span>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-4 p-md-5">
                        <p class="card-title fw-bold mb-4">{{ $request->title }}</p>

                        @if($request->description)
                            <div class="text-dark" style=" word-break: break-all;">
                                {!! nl2br(e($request->description)) !!}
                            </div>
                        @else
                            <p class="text-muted fst-italic mb-0">Nenhuma descrição fornecida.</p>
                        @endif

                        @if ($request->attachment_path)
                            <hr>
                            <h5 class="card-title text-muted small text-uppercase mb-3">Anexo</h5>
                            <p class="mb-0">
                                <i class="bi bi-paperclip me-2"></i>
                                <a href="{{ Storage::url($request->attachment_path) }}" target="_blank"
                                    class="text-decoration-none fw-semibold">
                                    {{ $request->attachment_original_name ?? 'Baixar Anexo' }}
                                </a>
                            </p>
                        @endif

                        {{-- Informações de Fechamento --}}
                        @if($request->status === \App\Enums\Request\RequestStatus::COMPLETED && $request->closure_description)
                            <hr>
                            <h5 class="card-title text-muted small text-uppercase mb-3">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                Como foi resolvido
                            </h5>
                            <div class="bg-light rounded p-3 mb-3">
                                <p class="mb-0">{{ $request->closure_description }}</p>
                            </div>

                            @if($request->closure_media_path)
                                <div class="mb-3">
                                    @if($request->closure_media_type === 'photo')
                                        <img src="{{ Storage::url($request->closure_media_path) }}" alt="Foto da solução"
                                            class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                                    @else
                                        <video controls class="w-100 rounded shadow-sm" style="max-height: 400px;">
                                            <source src="{{ Storage::url($request->closure_media_path) }}" type="video/mp4">
                                            Seu navegador não suporta vídeos.
                                        </video>
                                    @endif
                                </div>
                            @endif

                            <div class="text-muted small">
                                <i class="bi bi-person-check me-1"></i>
                                Fechado por <strong>{{ $request->closedBy->name }}</strong> em
                                <strong>{{ $request->closed_at->format('d/m/Y \à\s H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title text-muted small text-uppercase mb-0">Detalhes</h5>
                            <div class="d-flex">
                                @can('update', $request)
                                    <a href="{{ route('requests.edit', $request) }}"
                                        class="btn btn-link btn-sm p-0 text-secondary me-2" title="Editar Solicitação">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                @endcan
                                @can('delete', $request)
                                    <form action="{{ route('requests.destroy', $request) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja excluir esta solicitação?');"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-danger"
                                            title="Excluir Solicitação">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-4 col-form-label text-muted fw-bold small">Status</label>
                            <div class="col-8">
                                <span class="badge rounded-pill bg-{{ $request->status->colors() }}">
                                    {{ $request->status->getLabel() }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-4 col-form-label text-muted fw-bold small">Prioridade</label>
                            <div class="col-8">
                                <strong class="badge rounded-pill bg-{{ $request->priority->colors() }}">
                                    {{ $request->priority->getLabel() }}
                                </strong>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-4 col-form-label text-muted fw-bold small">Tipo</label>
                            <div class="col-8">
                                <p class="form-control-plaintext py-0 text-dark">{{ $request->type->getLabel() }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-4 col-form-label text-muted fw-bold small">Área</label>
                            <div class="col-8">
                                <p class="form-control-plaintext py-0 text-dark">{{ $request->area->name }}</p>
                            </div>
                        </div>

                        @if($request->pdv)
                            <div class="row">
                                <label class="col-4 col-form-label text-muted fw-bold small">PDV</label>
                                <div class="col-8">
                                    <a href="{{ route('pdvs.show', $request->pdv) }}"
                                        class="form-control-plaintext py-0 text-decoration-none">{{ $request->pdv->name }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title text-muted small text-uppercase mb-0">Responsáveis</h5>
                            @can('assign', $request)
                                <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="modal"
                                    data-bs-target="#assignUsersModal_{{ $request->id }}" title="Atribuir responsável">
                                    <i class="bi bi-person-plus fs-5"></i>
                                </button>
                            @endcan
                        </div>

                        @forelse ($request->assignees as $assignee)
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <span class="avatar-initials bg-secondary text-white">
                                            {{ Str::substr($assignee->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <span class="fw-bold">{{ $assignee->name }}</span>
                                    </div>
                                </div>
                                @can('assign', $request)
                                    <form action="{{ route('requests.assignees.detach', [$request, $assignee]) }}" method="POST"
                                        onsubmit="return confirm('Tem certeza que deseja remover {{ $assignee->name }} deste chamado?');"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-danger"
                                            title="Remover responsável">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @empty
                            <p class="text-muted small mb-0">Nenhum responsável atribuído.</p>
                        @endforelse
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-4">
                        <h5 class="card-title text-muted small text-uppercase mb-4">Informações</h5>

                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bi bi-person me-2 fs-5"></i>
                                <span>Solicitante: <strong
                                        class="text-dark">{{ $request->requester->name }}</strong></span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bi bi-clock me-2 fs-5"></i>
                                <span>Criado em: <strong
                                        class="text-dark">{{ $request->created_at->format('d/m/Y') }}</strong></span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bi bi-clock-history me-2 fs-5"></i>
                                <span>Atualizado em: <strong
                                        class="text-dark">{{ $request->updated_at->format('d/m/Y') }}</strong></span>
                            </li>
                            @if($request->due_at)
                                <li class="mb-2 d-flex align-items-center">
                                    <i class="bi bi-calendar-x me-2 fs-5 text-danger"></i>
                                    <span>Prazo: <strong
                                            class="text-danger">{{ $request->due_at->format('d/m/Y') }}</strong></span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <x-requests.modals.assign-users :request="$request" :availableAssignees="$availableAssignees" />
</x-app-layout>