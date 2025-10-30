<x-list-layout
    :collection="$requests"
    :searchRoute="route('requests.index')"
    :createRoute="route('requests.create')"
    createText="Abrir Chamado"
    searchPlaceholder="Buscar por título, área ou requisitante..."
    deleteModalText="Tem certeza que deseja excluir este chamado?">

    {{-- Título principal da página --}}
    <x-slot:header>
        Meus Chamados e Fila
    </x-slot:header>

    {{-- Cabeçalho da tabela para a visualização em desktop --}}
    <x-slot:tableHeader>
        <tr class="text-muted small">
            <th scope="col" class="py-3">TÍTULO</th>
            <th scope="col" class="py-3">ÁREA</th>
            <th scope="col" class="py-3">REQUISITANTE</th>
            <th scope="col" class="py-3">STATUS</th>
            <th scope="col" class="py-3">PRIORIDADE</th>
            <th scope="col" class="py-3">ABERTO EM</th>
            <th scope="col" class="py-3 text-end">AÇÕES</th>
        </tr>
    </x-slot:tableHeader>

    {{-- Loop para gerar as linhas da tabela (visualização em desktop) --}}
    @foreach ($requests as $requestItem)
        {{-- Usamos $requestItem para evitar conflito com a variável global $request --}}
        <tr class="border-bottom" data-href="{{ route('requests.show', $requestItem) }}">
            <td class="py-3 fw-bold">{{ $requestItem->title }}</td>
            <td class="py-3">{{ $requestItem->area->name ?? 'N/A' }}</td>
            <td class="py-3">{{ $requestItem->requester->name ?? 'N/A' }}</td>
            <td class="py-3">
                {{-- Usando o label do Enum --}}
                <span class="badge rounded-pill bg-light text-dark">
                    {{ \App\Enums\Request\RequestStatus::labels()[$requestItem->status->value] ?? $requestItem->status->value }}
                </span>
            </td>
            <td class="py-3">
                {{-- Usando o label e a cor do Enum --}}
                @php
                    $priorityValue = $requestItem->priority->value;
                    $priorityLabel = \App\Enums\Request\RequestPriority::labels()[$priorityValue] ?? $priorityValue;
                    $priorityColor = \App\Enums\Request\RequestPriority::colors()[$priorityValue] ?? 'secondary';
                @endphp
                <span class="badge rounded-pill bg-{{ $priorityColor }}">
                    {{ $priorityLabel }}
                </span>
            </td>
            <td class="py-3 small text-muted">{{ $requestItem->created_at->format('d/m/Y') }}</td>
            <td class="py-3 text-end">
                <div class="btn-group btn-group-sm" role="group">
                    {{-- Botão Editar --}}
                    {{-- A Policy controlará se o usuário pode ou não editar --}}
                    @can('update', $requestItem)
                        <a href="{{ route('requests.edit', $requestItem) }}" class="btn btn-outline-primary" title="Editar">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                    @endcan
                    {{-- Botão Excluir (aciona o modal do list-layout) --}}
                    {{-- A Policy controlará quem pode excluir --}}
                    @can('delete', $requestItem)
                        <button type="button" class="btn btn-outline-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                data-action="{{ route('requests.destroy', $requestItem) }}"
                                title="Excluir">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    @endcan
                </div>
            </td>
        </tr>
    @endforeach

    {{-- Loop para gerar os cards (visualização em mobile) --}}
    <x-slot:mobileList>
        @foreach ($requests as $requestItem)
            <a href="{{ route('requests.show', $requestItem) }}" class="text-decoration-none text-dark">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                             <h5 class="card-title mb-0">{{ $requestItem->title }}</h5>
                             @php
                                $priorityValue = $requestItem->priority->value;
                                $priorityLabel = \App\Enums\Request\RequestPriority::labels()[$priorityValue] ?? $priorityValue;
                                $priorityColor = \App\Enums\Request\RequestPriority::colors()[$priorityValue] ?? 'secondary';
                            @endphp
                            <span class="badge rounded-pill bg-{{ $priorityColor }} flex-shrink-0 ms-2">
                                {{ $priorityLabel }}
                            </span>
                        </div>
                        <p class="card-text small text-muted mb-1">
                            Área: {{ $requestItem->area->name ?? 'N/A' }} |
                            Status: {{ \App\Enums\Request\RequestStatus::labels()[$requestItem->status->value] ?? $requestItem->status->value }}
                        </p>
                        <p class="card-text small text-muted">
                            Aberto por {{ $requestItem->requester->name ?? 'N/A' }} em {{ $requestItem->created_at->format('d/m/Y') }}
                         </p>
                    </div>
                     {{-- Ações poderiam ser adicionadas no card-footer se necessário --}}
                </div>
            </a>
        @endforeach
    </x-slot:mobileList>

    {{-- Conteúdo para quando a busca não retorna resultados ou a tabela está vazia --}}
    <x-slot:emptyState>
        <div class="text-center py-5">
            <h5>Nenhum chamado encontrado.</h5>
            @if(request('search'))
                <p><a href="{{ route('requests.index') }}" class="small">Limpar busca</a></p>
            @else
                 <p class="text-muted">Parece que não há chamados abertos ou visíveis para você no momento.</p>
            @endif
             <a href="{{ route('requests.create') }}" class="btn btn-sm btn-primary mt-2">
                <i class="bi bi-plus-lg me-1"></i> Abrir um Novo Chamado
            </a>
        </div>
    </x-slot:emptyState>

</x-list-layout>