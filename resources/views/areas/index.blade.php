<x-list-layout 
    :collection="$areas"
    :searchRoute="route('areas.index')"
    :createRoute="route('areas.create')"
    createText="Nova Área"
    searchPlaceholder="Buscar por nome..."
    deleteModalText="Tem certeza que deseja excluir esta área? Equipes associadas terão seu vínculo removido, mas não serão excluídas.">

    {{-- Título principal da página --}}
    <x-slot:header>
        Áreas Organizacionais
    </x-slot:header>

    {{-- Cabeçalho da tabela para a visualização em desktop --}}
    <x-slot:tableHeader>
        <tr class="text-muted small">
            <th scope="col" class="py-3">NOME DA ÁREA</th>
            <th scope="col" class="py-3">SLUG</th>
            <th scope="col" class="py-3">DESCRIÇÃO</th>
            <th scope="col" class="py-3 text-end">AÇÕES</th>
        </tr>
    </x-slot:tableHeader>

    {{-- Loop para gerar as linhas da tabela (visualização em desktop) --}}
    @foreach ($areas as $area)
        <tr class="border-bottom" data-href="{{ route('areas.show', $area) }}">
            <td class="py-3">{{ $area->name }}</td>
            <td class="py-3">
                <code class="small">{{ $area->slug }}</code>
            </td>
            <td class="py-3">{{ Str::limit($area->description, 50) }}</td>
            <td class="py-3 text-end">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('areas.edit', $area) }}" class="btn btn-outline-primary" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal" 
                            data-action="{{ route('areas.destroy', $area) }}" 
                            title="Excluir">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach

    {{-- Loop para gerar os cards (visualização em mobile) --}}
    <x-slot:mobileList>
        @foreach ($areas as $area)
            <a href="{{ route('areas.show', $area) }}" class="text-decoration-none text-dark">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-1">{{ $area->name }}</h5>
                        <p class="card-text small text-muted">{{ Str::limit($area->description, 100) }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </x-slot:mobileList>

    {{-- Conteúdo para quando a busca não retorna resultados ou a tabela está vazia --}}
    <x-slot:emptyState>
        <div class="text-center py-5">
            <h5>Nenhuma área encontrada.</h5>
            @if(request('search'))
                <a href="{{ route('areas.index') }}" class="d-block mt-2 small">Limpar busca</a>
            @endif
        </div>
    </x-slot:emptyState>

</x-list-layout>