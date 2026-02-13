<x-app-layout>
    <x-slot:header>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-custom px-3 py-2">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none"><i class="bi bi-house-door"></i> Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Clientes</li>
            </ol>
        </nav>
    </x-slot:header>

    {{-- Container Principal --}}
    <div class="container-fluid">
        <div class="bg-white shadow-sm rounded p-4">

            {{-- Cabeçalho Interno --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div class="d-flex align-items-center gap-3">
                    <h2 class="fw-bold mb-0 fs-3">Gerenciar Clientes</h2>
                    <a href="{{ route('clients.create') }}" class="btn btn-primary px-4 rounded-3 ms-2">
                        <i class="bi bi-plus-lg me-1"></i> Novo Cliente
                    </a>
                </div>
            </div>

            {{-- Filtros e Busca --}}
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
                                <a class="nav-link {{ request('type') == $typeEnum->value ? 'active fw-bold text-dark' : 'text-muted' }}"
                                    href="{{ route('clients.index', ['type' => $typeEnum->value]) }}">
                                    {{ $typeEnum->getLabel() }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-12 col-md-auto">
                    <form action="{{ route('clients.index') }}" method="GET">
                        <div class="input-group bg-white border rounded-3 overflow-hidden">
                            <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="search" name="search" value="{{ request('search') }}"
                                class="form-control border-0 shadow-none ps-2" placeholder="Buscar por nome ou CNPJ..."
                                style="min-width: 250px;">
                        </div>
                        @if(request('type'))
                            <input type="hidden" name="type" value="{{ request('type') }}">
                        @endif
                    </form>
                </div>
            </div>

            <div class="card border-0 overflow-hidden">
                <div class="card-body p-0">
                    @if($clients->isEmpty())
                        <div class="text-center py-5">
                            <div class="mb-3 text-muted">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                            <h5 class="fw-bold">Nenhum Cliente encontrado</h5>
                            @if(request('search') || request('type'))
                                <p class="text-muted">Tente ajustar seus filtros de busca.</p>
                                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm mt-2">Limpar
                                    Filtros</a>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom">
                                    <tr class="text-muted small fw-bold">
                                        <th scope="col" class="py-3 ps-4" style="width: 60px;">#</th>
                                        <th scope="col" class="py-3">Cliente</th>
                                        <th scope="col" class="py-3">CNPJ / Local</th>
                                        <th scope="col" class="py-3">Tipo</th>
                                        <th scope="col" class="py-3 text-end pe-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($clients as $client)
                                                                    <tr style="cursor: pointer;"
                                                                        onclick="window.location='{{ route('clients.show', $client) }}'">
                                                                        <td class="py-3 ps-4 fw-bold text-muted">
                                                                            {{ $clients->firstItem() + $loop->index }}
                                                                        </td>

                                                                        <td class="py-3">
                                                                            <div class="fw-bold text-dark text-truncate" style="max-width: 250px;"
                                                                                title="{{ $client->name }}">
                                                                                {{ $client->name }}
                                                                            </div>
                                                                        </td>

                                                                        <td class="py-3">
                                                                            <div class="d-flex flex-column" style="max-width: 200px;">
                                                                                <span class="text-dark small fw-medium">{{ $client->cnpj }}</span>
                                                                                @if($client->city)
                                                                                    <span
                                                                                        class="text-muted x-small">{{ $client->city }}/{{ $client->state }}</span>
                                                                                @endif
                                                                            </div>
                                                                        </td>

                                                                        <td class="py-3">
                                                                            @php
                                                                                $typeValue = is_object($client->type) ? $client->type->value : $client->type;

                                                                                $color = match ($typeValue) {
                                                                                    'commercial' => 'primary',
                                                                                    'residential' => 'success',
                                                                                    'shortstay' => 'info',
                                                                                    default => 'secondary'
                                                                                };
                                                                            @endphp
                                         <span
                                                                                class="badge rounded-pill bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}">
                                                                                {{ $client->type instanceof \App\Enums\Client\ClientType ? $client->type->getLabel() : ucfirst($client->type) }}
                                                                            </span>
                                                                        </td>

                                                                        <td class="py-3 text-end" onclick="event.stopPropagation();">
                                                                            <div class="d-flex justify-content-end gap-2">
                                                                                {{-- Botão Editar --}}
                                                                                <a href="{{ route('clients.edit', $client) }}"
                                                                                    class="btn btn-outline-primary btn-sm rounded-2 border-0" title="Editar"
                                                                                    style="background-color: rgba(64, 128, 246, 0.05);">
                                                                                    <i class="bi bi-pencil-fill"></i>
                                                                                </a>

                                                                                {{-- Botão Excluir --}}
                                                                                <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                                                                    class="d-inline">
                                                                                    @csrf @method('DELETE')
                                                                                    <button type="submit"
                                                                                        class="btn btn-outline-danger btn-sm rounded-2 border-0"
                                                                                        title="Excluir" style="background-color: rgba(220, 53, 69, 0.05);"
                                                                                        onclick="return confirm('Tem certeza que deseja excluir este Cliente?');">
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
    </div>
</x-app-layout>