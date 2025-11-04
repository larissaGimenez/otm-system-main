@props(['client'])

<x-ui.page-context-header :title="$client->name">
    {{-- Badges de status/atributos do cliente --}}
    <x-slot name="status">
        <span class="badge rounded-pill bg-light text-dark border">
            {{ $client->type->getLabel() }}
        </span>

        @if(!empty($client->cnpj))
            <span class="badge bg-light text-dark border ms-2">
                CNPJ: {{ $client->cnpj }}
            </span>
        @endif

        @isset($client->status)
            <span class="badge rounded-pill ms-2 {{ $client->status === 'Ativo' ? 'bg-success' : 'bg-secondary' }}">
                {{ $client->status }}
            </span>
        @endisset
    </x-slot>

    {{-- Ações do cabeçalho --}}
    <x-slot name="actions">
        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-fill me-1"></i> Editar
        </a>
    </x-slot>
</x-ui.page-context-header>
