<x-list-layout 
    :collection="$users"
    :searchRoute="route('management.users.index')"
    :createRoute="route('management.users.create')"
    createText="Criar Novo Usuário"
    searchPlaceholder="Buscar por nome, e-mail ou telefone..."
    deleteModalText="Tem certeza que deseja excluir este usuário?">

    <x-slot:header>
        Gerenciar Usuários
    </x-slot:header>

    <x-slot:tableHeader>
        <tr class="text-muted small">
            <th scope="col" class="py-3">Nome</th>
            <th scope="col" class="py-3">Cargo</th>
            <th scope="col" class="py-3">E-mail</th>
            <th scope="col" class="py-3">Telefone</th>
            <th scope="col" class="py-3">Status</th>
            <th scope="col" class="py-3 text-end">Ações</th>
        </tr>
    </x-slot:tableHeader>

    {{-- Linhas da Tabela (Desktop) --}}
    @foreach ($users as $user)
        <tr class="border-bottom" data-href="{{ route('management.users.show', $user) }}">
            <td class="py-2">{{ $user->name }}</td>
            <td class="py-2">{{ $user->getRoleName() }}</td>
            <td class="py-2">{{ $user->email }}</td>
            <td class="py-2">{{ $user->phone ?? 'N/A' }}</td>
            <td class="py-2">
                <span class="badge rounded-pill {{ $user->trashed() ? 'bg-danger' : 'bg-success' }}">
                    {{ $user->trashed() ? 'Inativo' : 'Ativo' }}
                </span>
            </td>
            <td class="py-2 text-end">
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('management.users.edit', $user) }}" class="btn btn-outline-primary" title="Editar">
                        <i class="bi bi-pencil-fill"></i>
                    </a>
                    <button type="button" class="btn btn-outline-danger" 
                            data-bs-toggle="modal" data-bs-target="#deleteModal" 
                            data-action="{{ route('management.users.destroy', $user) }}" 
                            title="Excluir">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach

    {{-- Lista de Cards (Mobile) --}}
    <x-slot:mobileList>
        @foreach ($users as $user)
            <a href="{{ route('management.users.show', $user) }}" class="text-decoration-none text-dark">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">{{ $user->name }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $user->getRoleName() }}</h6>
                            </div>
                            <span class="badge rounded-pill {{ $user->trashed() ? 'bg-danger' : 'bg-success' }}">
                                {{ $user->trashed() ? 'Inativo' : 'Ativo' }}
                            </span>
                        </div>
                        <hr class="my-2">
                        <p class="card-text small mb-1"><strong>E-mail:</strong> {{ $user->email }}</p>
                        <p class="card-text small"><strong>Telefone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </a>
        @endforeach
    </x-slot:mobileList>

    {{-- Mensagem de "Nenhum Resultado" --}}
    <x-slot:emptyState>
        <div class="text-center py-5">
            <h5>Nenhum usuário encontrado.</h5>
            @if(request('search'))
                <a href="{{ route('management.users.index') }}" class="d-block mt-2 small">Limpar busca</a>
            @endif
        </div>
    </x-slot:emptyState>

</x-list-layout>