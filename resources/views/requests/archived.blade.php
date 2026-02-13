<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid px-0">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                                Chamados Arquivados
                            </h2>
                            <p class="text-muted mb-0 small">Chamados concluídos que foram arquivados</p>
                        </div>
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Voltar ao Kanban
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="container-fluid">
        <x-ui.flash-message />

        @if($requests->isEmpty())
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-archive display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Nenhum chamado arquivado</h5>
                    <p class="text-muted">Chamados concluídos podem ser arquivados para organização.</p>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Chamado</th>
                                    <th class="px-4 py-3">Área</th>
                                    <th class="px-4 py-3">Solicitante</th>
                                    <th class="px-4 py-3">Arquivado em</th>
                                    <th class="px-4 py-3">Arquivado por</th>
                                    <th class="px-4 py-3 text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('requests.show', $request) }}"
                                                    class="fw-bold text-decoration-none text-dark">
                                                    {{ $request->title }}
                                                </a>
                                                <small class="text-muted">
                                                    <span class="badge bg-{{ $request->priority->colors() }} rounded-pill me-1"
                                                        style="font-size: 0.65rem;">
                                                        {{ $request->priority->getLabel() }}
                                                    </span>
                                                    {{ $request->type->getLabel() }}
                                                </small>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light text-dark border">
                                                {{ $request->area->name }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $request->requester->name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3 me-1"></i>
                                                {{ $request->archived_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $request->archivedBy->name ?? 'Sistema' }}
                                        </td>
                                        <td class="px-4 py-3 text-end">
                                            <form action="{{ route('requests.unarchive', $request) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success"
                                                    title="Restaurar chamado">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                    Restaurar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Paginação --}}
            <div class="mt-3">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-app-layout>