@props(['area'])

<x-ui.page-context-header :title="$area->name">
    <x-slot name="actions">
        <a href="{{ route('areas.index') }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
        <a href="{{ route('areas.edit', $area) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-fill me-1"></i> Editar Área
        </a>
    </x-slot>
</x-ui.page-context-header>