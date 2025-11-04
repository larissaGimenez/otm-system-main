@props([
    'equipment',
])

<x-ui.page-context-header :title="$equipment->name">

    <x-slot name="status">
        <span class="badge rounded-pill {{ $equipment->status === 'Ativo' ? 'bg-success' : 'bg-secondary' }}">
            {{ $equipment->status }}
        </span>
    </x-slot>

    <x-slot name="actions">
        <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
        <a href="{{ route('equipments.edit', $equipment) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-fill me-1"></i> Editar Equipamento
        </a>
    </x-slot>

</x-ui.page-context-header>