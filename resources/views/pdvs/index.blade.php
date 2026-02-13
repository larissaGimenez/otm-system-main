<x-app-layout>
    <x-slot:header>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-custom px-3 py-2">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none"><i class="bi bi-house-door"></i> Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Pontos de Venda</li>
            </ol>
        </nav>
    </x-slot:header>

    {{-- Container Principal --}}
    <div class="container-fluid">
        <div class="bg-white shadow-sm rounded p-4">
            
            {{-- Cabeçalho Interno --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <h2 class="fw-bold mb-0 fs-3">Pontos de Venda</h2>
                    <a href="{{ route('pdvs.create') }}" class="btn btn-primary px-4 rounded-3 ms-2">
                        <i class="bi bi-plus-lg me-1"></i> Criar um novo
                    </a>
                </div>
            </div>

            {{-- Filtros e Busca --}}
            <div class="row g-3 align-items-center justify-content-between mb-4">
                {{-- Filtros por Status --}}
                <div class="col-12 col-md-auto">
                    <ul class="nav nav-underline border-bottom-0">
                        <li class="nav-item">
                            <a class="nav-link {{ !request('status') ? 'active fw-bold text-dark' : 'text-muted' }}" 
                               href="{{ route('pdvs.index') }}">
                                Todos <span class="small">({{ $pdvs->total() }})</span> 
                            </a>
                        </li>
                        @foreach($allStatuses as $status)
                            <li class="nav-item">
                                <a class="nav-link {{ request('status') == $status->slug ? 'active fw-bold text-dark' : 'text-muted' }}" 
                                   href="{{ route('pdvs.index', ['status' => $status->slug]) }}">
                                    {{ $status->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-12 col-md-auto">
                    <form action="{{ route('pdvs.index') }}" method="GET">
                        <div class="input-group bg-white border rounded-3 overflow-hidden">
                            <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   class="form-control border-0 shadow-none ps-2" 
                                   placeholder="Buscar PDV..." 
                                   style="min-width: 250px;">
                        </div>
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </form>
                </div>
            </div>

            <div class="card border-0 overflow-hidden">
                <div class="card-body p-0">
                    @if($pdvs->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3 text-muted">
                                <i class="bi bi-inbox fs-1"></i>
                            </div>
                            <h5 class="fw-bold">Nenhum Ponto de Venda encontrado</h5>
                            @if(request('search') || request('status'))
                                <p class="text-muted">Tente ajustar seus filtros de busca.</p>
                                <a href="{{ route('pdvs.index') }}" class="btn btn-outline-secondary btn-sm mt-2">Limpar Filtros</a>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom">
                                    <tr class="text-muted small fw-bold">
                                        <th scope="col" class="py-3 ps-4">Nome</th>
                                        <th scope="col" class="py-3">Cliente</th>
                                        <th scope="col" class="py-3">Status</th>
                                        <th scope="col" class="py-3 text-end pe-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pdvs as $pdv)
                                        <tr style="cursor: pointer;" onclick="window.location='{{ route('pdvs.show', $pdv) }}'">
                                            
                                            <td class="py-3 ps-4">
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">
                                                    {{ $pdv->name }}
                                                </div>
                                            </td>

                                            <td class="py-3">
                                                <div class="text-secondary small" title="{{ $pdv->client->name ?? '' }}">
                                                    {{ $pdv->client->name ?? 'Sem Cliente' }}
                                                </div>
                                            </td>

                                            <td class="py-3">
                                                @php
                                                    $statusColor = $pdv->status->color ?? 'secondary';
                                                @endphp
                                                <span class="badge rounded-pill bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }}">
                                                    {{ $pdv->status->name ?? 'N/A' }}
                                                </span>
                                            </td>

                                            <td class="py-3 text-end" onclick="event.stopPropagation();">
                                                <div class="d-flex justify-content-end gap-2">
                                                    {{-- Botão Editar --}}
                                                    <a href="{{ route('pdvs.edit', $pdv) }}" 
                                                       class="btn btn-outline-primary btn-sm rounded-2 border-0" 
                                                       title="Editar"
                                                       style="background-color: rgba(64, 128, 246, 0.05);"> 
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>

                                                    {{-- Botão Excluir --}}
                                                    <form action="{{ route('pdvs.destroy', $pdv) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-outline-danger btn-sm rounded-2 border-0" 
                                                                title="Excluir"
                                                                style="background-color: rgba(220, 53, 69, 0.05);"
                                                                onclick="return confirm('Excluir este PDV?');">
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

                        {{-- Paginação no estilo do modelo salvo --}}
                        @if($pdvs->hasPages())
                            <div class="d-flex justify-content-end border-top p-3 bg-light">
                                {{ $pdvs->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>