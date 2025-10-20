<x-list-layout 
    :collection="$pdvs"
    :searchRoute="route('pdvs.index')"
    :createRoute="route('pdvs.create')"
    createText="Novo Ponto de Venda"
    searchPlaceholder="Buscar por nome, tipo, status ou rua..."
    deleteModalText="Tem certeza que deseja excluir este Ponto de Venda?">

    {{-- Título principal da página --}}
    <x-slot:header>
        Gerenciar Pontos de Venda
    </x-slot:header>

    {{-- Cabeçalho da tabela para a visualização em desktop --}}
    <x-slot:tableHeader>
        <tr class="text-muted small">
            <th scope="col" class="py-3">NOME DO PDV</th>
            <th scope="col" class="py-3">TIPO</th>
            <th scope="col" class="py-3">ENDEREÇO</th>
            <th scope="col" class="py-3">STATUS</th>
            <th scope="col" class="py-3 text-end">AÇÕES</th>
        </tr>
    </x-slot:tableHeader>

    {{-- Loop para gerar as linhas da tabela (visualização em desktop) --}}
    @foreach ($pdvs as $pdv)
        <tr class="border-bottom" data-href="{{ route('pdvs.show', $pdv) }}">
            <td class="py-3">{{ $pdv->name }}</td>
            <td class="py-3">{{ $pdv->type }}</td>
            <td class="py-3">{{ $pdv->street ?? 'Endereço não informado' }}{{ $pdv->number ? ', ' . $pdv->number : '' }}</td>
            <td class="py-3">
                {{-- Exemplo de lógica de status. Ajuste as classes de cor conforme seus status --}}
                <span class="badge rounded-pill {{ $pdv->status === 'Ativo' ? 'bg-success' : 'bg-secondary' }}">
                    {{ $pdv->status }}
                </span>
            </td>
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
                                <h6 class="card-subtitle mb-2 text-muted">{{ $pdv->type }}</h6>
                            </div>
                            <span class="badge rounded-pill {{ $pdv->status === 'Ativo' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $pdv->status }}
                            </span>
                        </div>
                        <hr class="my-2">
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