<x-list-layout 
    :collection="$teams"
    :searchRoute="route('management.teams.index')"
    :createRoute="route('management.teams.create')"
    createText="Nova Equipe"
    searchPlaceholder="Buscar por nome ou status..."
    deleteModalText="Tem certeza que deseja excluir esta equipe? Esta ação não pode ser desfeita.">

    {{-- Título principal da página --}}
    <x-slot:header>
        Gerenciar Equipes
    </x-slot:header>

    {{-- Cabeçalho da tabela para a visualização em desktop --}}
    <x-slot:tableHeader>
        <tr class="text-muted small">
            <th scope="col" class="py-3">NOME DA EQUIPE</th>
            <th scope="col" class="py-3">STATUS</th>
            <th scope="col" class="py-3 text-end">AÇÕES</th>
        </tr>
    </x-slot:tableHeader>

    {{-- Loop para gerar as linhas da tabela (visualização em desktop) --}}
    @foreach ($teams as $team)
        <tr class="border-bottom" data-href="{{ route('management.teams.show', $team) }}">
            <td class="py-3">{{ $team->name }}</td>
            <td class="py-3">
                {{-- O badge exibirá o status salvo no banco --}}
                <span class="badge rounded-pill bg-secondary text-white">
                    {{ $team->status }}
                </span>
            </td>
            <td class="py-3 text-end">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('management.teams.edit', $team) }}" class="btn btn-outline-primary" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal" 
                            data-action="{{ route('management.teams.destroy', $team) }}" 
                            title="Excluir">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach

    {{-- Loop para gerar os cards (visualização em mobile) --}}
    <x-slot:mobileList>
        @foreach ($teams as $team)
            <a href="{{ route('management.teams.show', $team) }}" class="text-decoration-none text-dark">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{{ $team->name }}</h5>
                                <p class="card-text small text-muted">{{ Str::limit($team->description, 100) }}</p>
                            </div>
                            <span class="badge rounded-pill bg-secondary text-white">
                                {{ $team->status }}
                            </span>
                        </div>
                        {{-- Os botões de ação poderiam ser adicionados aqui se necessário para mobile --}}
                    </div>
                </div>
            </a>
        @endforeach
    </x-slot:mobileList>

    {{-- Conteúdo para quando a busca não retorna resultados ou a tabela está vazia --}}
    <x-slot:emptyState>
        <div class="text-center py-5">
            <h5>Nenhuma equipe encontrada.</h5>
            @if(request('search'))
                <a href="{{ route('management.teams.index') }}" class="d-block mt-2 small">Limpar busca</a>
            @endif
        </div>
    </x-slot:emptyState>

</x-list-layout>