<x-app-layout>
    <x-slot:header>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-sm mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">PDVs</li>
            </ol>
        </nav>
    </x-slot:header>

    <div class="container-fluid py-4">

        {{-- LINHA 1: TÍTULO, CONTADOR E BOTÃO DE CRIAÇÃO --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                {{-- Título --}}
                <h2 class="fw-bold mb-0 fs-2">Pontos de Venda</h2>
                
                {{-- Contador --}}
                <span class="text-muted fs-3 fw-light">({{ $pdvs->total() }})</span>
                
                {{-- Botão Novo --}}
                <a href="{{ route('pdvs.create') }}" class="btn btn-primary px-4 rounded-3 ms-2">
                    <i class="bi bi-plus-lg me-1"></i> Novo PDV
                </a>
            </div>
        </div>

        {{-- LINHA 2: ABAS DE FILTRO E BUSCA --}}
        <div class="row g-3 align-items-center justify-content-between mb-4">
            
            {{-- Lado Esquerdo: Abas --}}
            <div class="col-12 col-md-auto">
                <ul class="nav nav-underline border-bottom-0">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('status') ? 'active fw-bold text-dark' : 'text-muted' }}" 
                           href="{{ route('pdvs.index') }}">
                           Todos <span class="small">({{ $pdvs->total() }})</span>
                        </a>
                    </li>
                    {{-- Filtros de Status --}}
                    @foreach(\App\Enums\Pdv\PdvStatus::cases() as $status)
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == $status->value ? 'active fw-bold text-dark' : 'text-muted' }}" 
                               href="{{ route('pdvs.index', ['status' => $status->value]) }}">
                                {{ $status->getLabel() }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Lado Direito: Barra de Busca --}}
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
                               placeholder="Buscar PDVs..." 
                               style="min-width: 250px;">
                    </div>
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                </form>
            </div>
        </div>

        {{-- LINHA 3: TABELA --}}
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">
                
                @if($pdvs->isEmpty())
                    {{-- Empty State --}}
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
                                <tr class="text-muted small text-uppercase fw-bold">
                                    <th scope="col" class="py-3 ps-4" style="width: 60px;">#</th>
                                    <th scope="col" class="py-3">PDV</th>
                                    <th scope="col" class="py-3">Cliente</th>
                                    <th scope="col" class="py-3">Status</th>
                                    <th scope="col" class="py-3 text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pdvs as $pdv)
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('pdvs.show', $pdv) }}'">
                                        {{-- NÚMERO --}}
                                        <td class="py-3 ps-4 fw-bold text-muted">
                                            {{ $pdvs->firstItem() + $loop->index }}
                                        </td>

                                        {{-- NOME E ENDEREÇO --}}
                                        <td class="py-3">
                                            <div class="fw-bold text-truncate text-dark" style="max-width: 250px;" title="{{ $pdv->name }}">
                                                {{ $pdv->name }}
                                            </div>
                                            <div class="small text-muted text-truncate" style="max-width: 250px;">
                                                {{ $pdv->street ?? '-' }}
                                            </div>
                                        </td>

                                        {{-- CLIENTE --}}
                                        <td class="py-3">
                                            <div class="text-truncate text-secondary" style="max-width: 200px;" title="{{ $pdv->client->name ?? '' }}">
                                                {{ $pdv->client->name ?? 'Sem Cliente' }}
                                            </div>
                                        </td>

                                        {{-- STATUS --}}
                                        <td class="py-3">
                                            <span class="badge rounded-pill bg-{{ $pdv->status->getColorClass() }} bg-opacity-10 text-{{ $pdv->status->getColorClass() }} border border-{{ $pdv->status->getColorClass() }}">
                                                {{ $pdv->status->getLabel() }}
                                            </span>
                                        </td>

                                        {{-- AÇÕES --}}
                                        <td class="py-3 text-end pe-4" onclick="event.stopPropagation();">
                                            <div class="btn-group btn-group-sm opacity-75 hover-opacity-100">
                                                {{-- Botão Editar --}}
                                                <a href="{{ route('pdvs.edit', $pdv) }}" class="btn btn-light text-primary border" title="Editar">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>

                                                {{-- Botão Excluir (Formulário + Confirm Nativo) --}}
                                                <form action="{{ route('pdvs.destroy', $pdv) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Tem certeza que deseja excluir este Ponto de Venda?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-light text-danger border" title="Excluir">
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

                    {{-- Paginação --}}
                    @if($pdvs->hasPages())
                        <div class="d-flex justify-content-end border-top p-3 bg-light">
                            {{ $pdvs->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>