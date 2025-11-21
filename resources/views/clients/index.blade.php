<x-app-layout>
    <x-slot:header>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-sm mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Clientes</li>
            </ol>
        </nav>
    </x-slot:header>

    <div class="container-fluid py-4">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <h2 class="fw-bold mb-0 fs-2">Gerenciar Clientes</h2>
                <span class="text-muted fs-3 fw-light">({{ $clients->total() }})</span>
                <a href="{{ route('clients.create') }}" class="btn btn-primary px-4 rounded-3 ms-2">
                    <i class="bi bi-plus-lg me-1"></i> Novo Cliente
                </a>
            </div>
        </div>

        <div class="row g-3 align-items-center justify-content-between mb-4">
            {{-- Filtros por Tipo (Enum) --}}
            <div class="col-12 col-md-auto">
                <ul class="nav nav-underline border-bottom-0">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('type') ? 'active fw-bold text-dark' : 'text-muted' }}" 
                           href="{{ route('clients.index') }}">
                           Todos <span class="small">({{ $clients->total() }})</span>
                        </a>
                    </li>
                    @foreach($types as $typeEnum)
                        <li class="nav-item">
                            {{-- Verifica se o parametro 'type' na URL bate com o valor do Enum --}}
                            <a class="nav-link {{ request('type') == $typeEnum->value ? 'active fw-bold text-dark' : 'text-muted' }}" 
                               href="{{ route('clients.index', ['type' => $typeEnum->value]) }}">
                                {{ $typeEnum->getLabel() }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Barra de Busca --}}
            <div class="col-12 col-md-auto">
                <form action="{{ route('clients.index') }}" method="GET">
                    <div class="input-group bg-white border rounded-3 overflow-hidden">
                        <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               class="form-control border-0 shadow-none ps-2" 
                               placeholder="Buscar por nome ou CNPJ..." 
                               style="min-width: 250px;">
                    </div>
                    {{-- Mantém o filtro de tipo ativo ao buscar --}}
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">
                @if($clients->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-3 text-muted">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                        <h5 class="fw-bold">Nenhum Cliente encontrado</h5>
                        @if(request('search') || request('type'))
                            <p class="text-muted">Tente ajustar seus filtros de busca.</p>
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm mt-2">Limpar Filtros</a>
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr class="text-muted small text-uppercase fw-bold">
                                    <th scope="col" class="py-3 ps-4" style="width: 60px;">#</th>
                                    <th scope="col" class="py-3">Cliente</th>
                                    <th scope="col" class="py-3">CNPJ / Local</th>
                                    <th scope="col" class="py-3">Tipo</th>
                                    <th scope="col" class="py-3 text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr style="cursor: pointer;" onclick="window.location='{{ route('clients.show', $client) }}'">
                                        <td class="py-3 ps-4 fw-bold text-muted">
                                            {{ $clients->firstItem() + $loop->index }}
                                        </td>
                                        
                                        {{-- Coluna Nome --}}
                                        <td class="py-3">
                                            <div class="fw-bold text-truncate text-dark" style="max-width: 250px;" title="{{ $client->name }}">
                                                {{ $client->name }}
                                            </div>
                                        </td>

                                        {{-- Coluna Secundária (CNPJ ou Cidade) --}}
                                        <td class="py-3">
                                            <div class="d-flex flex-column" style="max-width: 200px;">
                                                <span class="text-dark small fw-medium">{{ $client->cnpj }}</span>
                                                @if($client->city)
                                                    <span class="text-muted x-small">{{ $client->city }}/{{ $client->state }}</span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Coluna Tipo (Enum) com cores --}}
                                        <td class="py-3">
                                            @php
                                                // Define cores baseadas no value do Enum
                                                // Se você tiver cast no model, use $client->type->value, senão use $client->type
                                                $typeValue = is_object($client->type) ? $client->type->value : $client->type;
                                                
                                                $color = match($typeValue) {
                                                    'commercial' => 'primary',   // Azul
                                                    'residential' => 'success',  // Verde
                                                    'shortstay'   => 'info',     // Ciano
                                                    default       => 'secondary'
                                                };

                                                $label = is_object($client->type) ? $client->type->getLabel() : $client->type; 
                                                // Se não tiver cast no model para Enum, talvez precise chamar o Enum::tryFrom($client->type)->getLabel()
                                                // Assumindo que você tem casts no Model ou vai exibir direto
                                            @endphp

                                            <span class="badge rounded-pill bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}">
                                                {{-- Tenta pegar o label bonito, se não der, mostra o valor cru --}}
                                                {{ $client->type instanceof \App\Enums\Client\ClientType ? $client->type->getLabel() : ucfirst($client->type) }}
                                            </span>
                                        </td>

                                        {{-- Ações --}}
                                        <td class="py-3 text-end pe-4" onclick="event.stopPropagation();">
                                            <div class="btn-group btn-group-sm opacity-75 hover-opacity-100">
                                                <a href="{{ route('clients.edit', $client) }}" class="btn btn-light text-primary border" title="Editar">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <form action="{{ route('clients.destroy', $client) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Tem certeza que deseja excluir este Cliente?');">
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
                    @if($clients->hasPages())
                        <div class="d-flex justify-content-end border-top p-3 bg-light">
                            {{ $clients->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</x-app-layout>