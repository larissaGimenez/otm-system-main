<x-list-layout 
    :collection="$equipments"
    :searchRoute="route('equipments.index')"
    :createRoute="route('equipments.create')"
    createText="Novo Equipamento"
    searchPlaceholder="Buscar por nome, tipo ou descrição..."
    deleteModalText="Tem certeza que deseja excluir este Equipamento?">

    {{-- Título principal da página --}}
    <x-slot:header>
        Gerenciar Equipamentos
    </x-slot:header>

    {{-- Cabeçalho da tabela (desktop) --}}
    <x-slot:tableHeader>
        <tr class="text-muted small">
            <th scope="col" class="py-3">NOME DO EQUIPAMENTO</th>
            <th scope="col" class="py-3">TIPO</th>
            <th scope="col" class="py-3">DESCRIÇÃO</th>
            <th scope="col" class="py-3 text-end">AÇÕES</th>
        </tr>
    </x-slot:tableHeader>

    {{-- Linhas da tabela (desktop) --}}
    @foreach ($equipments as $equipment)
        <tr class="border-bottom" data-href="{{ route('equipments.show', $equipment) }}">
            <td class="py-3">{{ $equipment->name }}</td>
            <td class="py-3">{{ $equipment->type }}</td>
            <td class="py-3">
                {{ \Illuminate\Support\Str::limit($equipment->description ?? '—', 80) }}
            </td>
            <td class="py-3 text-end">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('equipments.edit', $equipment) }}" class="btn btn-outline-primary" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal" 
                            data-action="{{ route('equipments.destroy', $equipment) }}" 
                            title="Excluir">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach

    {{-- Lista em cartões (mobile) --}}
    <x-slot:mobileList>
        @foreach ($equipments as $equipment)
            <a href="{{ route('equipments.show', $equipment) }}" class="text-decoration-none text-dark">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{{ $equipment->name }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $equipment->type }}</h6>
                            </div>
                        </div>
                        <hr class="my-2">
                        <p class="card-text small mb-0">
                            <strong>Descrição:</strong>
                            {{ \Illuminate\Support\Str::limit($equipment->description ?? 'Não informada', 120) }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </x-slot:mobileList>

    {{-- Estado vazio / sem resultados --}}
    <x-slot:emptyState>
        <div class="text-center py-5">
            <h5>Nenhum Equipamento encontrado.</h5>
            @if(request('search'))
                <a href="{{ route('equipments.index') }}" class="d-block mt-2 small">Limpar busca</a>
            @endif
        </div>
    </x-slot:emptyState>

</x-list-layout>
