<x-list-layout 
    :collection="$pdvs"
    :searchRoute="route('pdvs.index')"
    :createRoute="route('pdvs.create')"
    createText="Novo Ponto de Venda"
    searchPlaceholder="Buscar por nome, CNPJ, tipo, status..."
    deleteModalText="Tem certeza que deseja excluir este Ponto de Venda?">

    {{-- Título principal da página --}}
    <x-slot:header>
        Gerenciar Pontos de Venda
    </x-slot:header>

    {{-- Cabeçalho da tabela para a visualização em desktop --}}
    <x-slot:tableHeader>
        <tr class="text-muted small">
            <th scope="col" class="py-3">PDV / CNPJ</th>
            <th scope="col" class="py-3">TIPO</th>
            <th scope="col" class="py-3">ENDEREÇO</th>
            <th scope="col" class="py-3">STATUS</th>
            <th scope="col" class="py-3 text-end">AÇÕES</th>
        </tr>
    </x-slot:tableHeader>

    {{-- Loop para gerar as linhas da tabela (visualização em desktop) --}}
    @foreach ($pdvs as $pdv)
        <tr class="border-bottom" data-href="{{ route('pdvs.show', $pdv) }}">
            {{-- COLUNA PDV / CNPJ --}}
            <td class="py-3">
                <div class="fw-bold">{{ $pdv->name }}</div>
                <div class="small text-muted">{{ $pdv->cnpj ?? 'CNPJ não informado' }}</div>
            </td>
            {{-- COLUNA TIPO (CORRIGIDO) --}}
            <td class="py-3">{{ $pdv->type->getLabel() }}</td>
            {{-- COLUNA ENDEREÇO --}}
            <td class="py-3">{{ $pdv->street ?? 'Endereço não informado' }}{{ $pdv->number ? ', ' . $pdv->number : '' }}</td>
            {{-- COLUNA STATUS (CORRIGIDO) --}}
            <td class="py-3">
                <span class="badge rounded-pill bg-{{ $pdv->status->getColorClass() }}">
                    {{ $pdv->status->getLabel() }}
                </span>
            </td>
            {{-- COLUNA AÇÕES --}}
            <td class="py-3 text-end">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('pdvs.edit', $pdv) }}" class="btn btn-outline-primary" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal" 
                            data-action="{{ route('pdvs.destroy', $pdv) }}" 
                            title="Excluir">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach

    {{-- Loop para gerar os cards (visualização em mobile) --}}
    <x-slot:mobileList>
        @foreach ($pdvs as $pdv)
            <a href="{{ route('pdvs.show', $pdv) }}" class="text-decoration-none text-dark">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{{ $pdv->name }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $pdv->type->getLabel() }}</h6>
                            </div>
                            <span class="badge rounded-pill bg-{{ $pdv->status->getColorClass() }}">
                                {{ $pdv->status->getLabel() }}
                            </span>
                        </div>
                        <hr class="my-2">
                        {{-- CNPJ ADICIONADO AO MOBILE --}}
                        <p class="card-text small mb-1">
                            <strong>CNPJ:</strong> {{ $pdv->cnpj ?? 'N/A' }}
                        </p>
                        <p class="card-text small mb-1">
                            <strong>Endereço:</strong> {{ $pdv->street ?? 'Não informado' }}{{ $pdv->number ? ', ' . $pdv->number : '' }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </x-slot:mobileList>

    {{-- Conteúdo para quando a busca não retorna resultados ou a tabela está vazia --}}
    <x-slot:emptyState>
        <div class="text-center py-5">
            <h5>Nenhum Ponto de Venda encontrado.</h5>
            @if(request('search'))
                <a href="{{ route('pdvs.index') }}" class="d-block mt-2 small">Limpar busca</a>
            @endif
        </div>
    </x-slot:emptyState>

</x-list-layout>