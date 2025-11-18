@props([
    'pdv',
])


<x-ui.page-context-header :title="$pdv->name">

    <x-slot name="status">
        <span class="badge rounded-pill bg-{{ $pdv->status?->color ?? 'secondary' }}">
            {{ $pdv->status?->name ?? 'Sem Status' }}
        </span>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('pdvs.index') }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
        <a href="{{ route('pdvs.edit', $pdv) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-fill me-1"></i> Editar PDV
        </a>
    </x-slot>

</x-ui.page-context-header>