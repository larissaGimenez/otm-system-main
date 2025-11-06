<x-app-layout>

    <x-ui.flash-message />

    <x-slot name="header">
        <div class="container-fluid px-0 col-lg-12 mx-auto">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Detalhes do Chamado
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="col-lg-12 mx-auto">
        <div class="row">
            <div class="col-lg-6">
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
                                <a href="{{ Storage::url($request->attachment_path) }}" target="_blank" class="text-decoration-none fw-semibold">
                                    {{ $request->attachment_original_name ?? 'Baixar Anexo' }}
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title text-muted small text-uppercase mb-0">Detalhes</h5>
                            <div class="d-flex">
                                @can('update', $request)
                                    <a href="{{ route('requests.edit', $request) }}" class="btn btn-link btn-sm p-0 text-secondary me-2" title="Editar Solicitação">
                                        <i class="bi bi-pencil fs-5"></i>
                                    </a>
                                @endcan
                                @can('delete', $request)
                                    <form action="{{ route('requests.destroy', $request) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta solicitação?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-danger" title="Excluir Solicitação">
                                            <i class="bi bi-trash fs-5"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-4 col-form-label text-muted fw-bold small">Status</label>
                            <div class="col-8">
                                <span class="badge bg-primary rounded-pill">{{ $request->status->getLabel() }}</span>
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
                                <a href="{{ route('pdvs.show', $request->pdv) }}" class="form-control-plaintext py-0 text-decoration-none">{{ $request->pdv->name }}</a>
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
                            <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="modal" data-bs-target="#assignUsersModal_{{ $request->id }}" title="Atribuir responsável">
                                <i class="bi bi-person-plus fs-5"></i>
                            </button>
                            @endcan
                        </div>

                        @forelse ($request->assignees as $assignee)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-shrink-0">
                                    <span class="avatar-initials bg-secondary text-white">
                                        {{ Str::substr($assignee->name, 0, 2) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <span class="fw-bold">{{ $assignee->name }}</span>
                                </div>
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
                                <span>Solicitante: <strong class="text-dark">{{ $request->requester->name }}</strong></span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bi bi-clock me-2 fs-5"></i>
                                <span>Criado em: <strong class="text-dark">{{ $request->created_at->format('d/m/Y') }}</strong></span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bi bi-clock-history me-2 fs-5"></i>
                                <span>Atualizado em: <strong class="text-dark">{{ $request->updated_at->format('d/m/Y') }}</strong></span>
                            </li>
                            @if($request->due_at)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="bi bi-calendar-x me-2 fs-5 text-danger"></i>
                                <span>Prazo: <strong class="text-danger">{{ $request->due_at->format('d/m/Y') }}</strong></span>
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