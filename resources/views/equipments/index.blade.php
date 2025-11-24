<x-app-layout>
    <x-slot:header>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-sm mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Equipamentos</li>
            </ol>
        </nav>
    </x-slot:header>

    <div class="container-fluid py-4">

        {{-- HEADER SUPERIOR --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <h2 class="fw-bold mb-0 fs-2">Equipamentos</h2>
                <span class="text-muted fs-3 fw-light">({{ $equipments->total() }})</span>
                <a href="{{ route('equipments.create') }}" class="btn btn-primary px-4 rounded-3 ms-2">
                    <i class="bi bi-plus-lg me-1"></i> Novo Equipamento
                </a>
            </div>
        </div>

        {{-- FILTRO + BUSCA --}}
        <div class="row g-3 align-items-center justify-content-between mb-4">

            <div class="col-12 col-md-auto">
                <form action="{{ route('equipments.index') }}" method="GET">
                    <div class="input-group bg-white border rounded-3 overflow-hidden">
                        <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control border-0 shadow-none ps-2"
                               placeholder="Buscar equipamentos..."
                               style="min-width: 250px;">
                    </div>
                </form>
            </div>
        </div>

        {{-- TABELA / LISTAGEM --}}
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">

                {{-- ESTADO VAZIO --}}
                @if($equipments->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3 text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                        </div>
                        <h5 class="fw-bold">Nenhum equipamento encontrado</h5>

                        @if(request('search'))
                            <p class="text-muted">Tente ajustar sua busca.</p>
                            <a href="{{ route('equipments.index') }}"
                               class="btn btn-outline-secondary btn-sm mt-2">
                                Limpar busca
                            </a>
                        @endif
                    </div>
                @else

                    {{-- TABELA --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr class="text-muted small text-uppercase fw-bold">
                                    <th class="py-3 ps-4" style="width: 60px;">#</th>
                                    <th class="py-3">Equipamento</th>
                                    <th class="py-3">Tipo</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Marca</th>
                                    <th class="py-3 text-end pe-4">Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($equipments as $equipment)
                                    <tr style="cursor: pointer;"
                                        onclick="window.location='{{ route('equipments.show', $equipment) }}'">

                                        {{-- NUMERAÇÃO --}}
                                        <td class="py-3 ps-4 fw-bold text-muted">
                                            {{ $equipments->firstItem() + $loop->index }}
                                        </td>

                                        {{-- NOME --}}
                                        <td class="py-3">
                                            <div class="fw-bold text-truncate text-dark"
                                                 style="max-width: 250px;"
                                                 title="{{ $equipment->name }}">
                                                {{ $equipment->name }}
                                            </div>
                                        </td>

                                        {{-- TIPO --}}
                                        <td class="py-3 text-secondary">
                                            {{ $equipment->type->name ?? '—' }}
                                        </td>

                                        {{-- STATUS --}}
                                        <td class="py-3">
                                            @if($equipment->status)
                                                <span class="badge rounded-pill 
                                                    bg-{{ $equipment->status->color }} bg-opacity-10
                                                    text-{{ $equipment->status->color }}
                                                    border border-{{ $equipment->status->color }}">
                                                    {{ $equipment->status->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>

                                        {{-- MARCA --}}
                                        <td class="py-3 text-secondary">
                                            {{ $equipment->brand ?? '—' }}
                                        </td>

                                        {{-- AÇÕES --}}
                                        <td class="py-3 text-end pe-4" onclick="event.stopPropagation();">
                                            <div class="btn-group btn-group-sm opacity-75 hover-opacity-100">
                                                <a href="{{ route('equipments.edit', $equipment) }}"
                                                   class="btn btn-light text-primary border"
                                                   title="Editar">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>

                                                <form action="{{ route('equipments.destroy', $equipment) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Excluir este equipamento?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-light text-danger border"
                                                            title="Excluir">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINAÇÃO --}}
                    @if($equipments->hasPages())
                        <div class="d-flex justify-content-end border-top p-3 bg-light">
                            {{ $equipments->links() }}
                        </div>
                    @endif

                @endif
            </div>
        </div>

    </div>
</x-app-layout>
