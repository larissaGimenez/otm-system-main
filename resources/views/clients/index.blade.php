<x-list-layout 
    :collection="$clients"
    :searchRoute="route('clients.index')"
    :createRoute="route('clients.create')"
    createText="Novo Cliente"
    searchPlaceholder="Buscar por nome, CNPJ, cidade..."
    deleteModalText="Tem certeza que deseja excluir este Cliente? Esta ação não pode ser desfeita.">

    {{-- Título principal da página --}}
    <x-slot:header>
        Gerenciar Clientes
    </x-slot:header>

    {{-- Cabeçalho da tabela para a visualização em desktop --}}
    <x-slot:tableHeader>
        <tr class="text-muted small">
            <th scope="col" class="py-3">Cliente / CNPJ</th>
            <th scope="col" class="py-3">Tipo</th>
            <th scope="col" class="py-3">Cidade / Estado</th>
            <th scope="col" class="py-3 text-end">Ações</th>
        </tr>
    </x-slot:tableHeader>

    {{-- Loop para gerar as linhas da tabela (visualização em desktop) --}}
    @foreach ($clients as $client)
        {{-- Adicionando data-href para tornar a linha clicável --}}
        <tr class="border-bottom" data-href="{{ route('clients.show', $client) }}">
            
            {{-- COLUNA CLIENTE / CNPJ --}}
            <td class="py-3">
                <div class="fw-bold">{{ $client->name }}</div>
                <div class="small text-muted">{{ $client->cnpj ?? 'CNPJ não informado' }}</div>
            </td>
            
            {{-- COLUNA TIPO --}}
            <td class="py-3">
                {{-- Assumindo que seu Enum ClientType tem o método getLabel() --}}
                <span class="badge bg-light text-dark border">{{ $client->type->getLabel() }}</span>
            </td>
            
            {{-- COLUNA CIDADE / ESTADO --}}
            <td class="py-3">
                {{ $client->city ?? 'N/A' }}{{ $client->state ? ', ' . $client->state : '' }}
            </td>
            
            {{-- COLUNA AÇÕES --}}
            <td class="py-3 text-end">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-primary" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal" 
                            data-action="{{ route('clients.destroy', $client) }}" 
                            title="Excluir">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach

    {{-- Loop para gerar os cards (visualização em mobile) --}}
    <x-slot:mobileList>
        @foreach ($clients as $client)
            <a href="{{ route('clients.show', $client) }}" class="text-decoration-none text-dark">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{{ $client->name }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $client->cnpj ?? 'N/A' }}</h6>
                            </div>
                            <span class="badge bg-light text-dark border">{{ $client->type->getLabel() }}</span>
                        </div>
                        <hr class="my-2">
                        <p class="card-text small mb-1">
                            <strong>Local:</strong> {{ $client->city ?? 'N/A' }}{{ $client->state ? ', ' . $client->state : '' }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </x-slot:mobileList>

    {{-- Conteúdo para quando a tabela está vazia --}}
    <x-slot:emptyState>
        <div class="text-center py-5">
            <h5>Nenhum Cliente encontrado.</h5>
            @if(request('search'))
                <a href="{{ route('clients.index') }}" class="d-block mt-2 small">Limpar busca</a>
            @endif
        </div>
    </x-slot:emptyState>

</x-list-layout>