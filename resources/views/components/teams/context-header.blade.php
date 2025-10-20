@props(['team'])

<x-ui.page-context-header :title="$team->name">
    {{-- Slot para o status da equipe --}}
    <x-slot name="status">
        @php
            $statusClass = $team->status === 'active' ? 'bg-success' : 'bg-secondary';
        @endphp
        <span class="badge rounded-pill {{ $statusClass }}">
            {{ ucfirst($team->status) }}
        </span>
    </x-slot>

    {{-- Slot para os botões de ação --}}
    <x-slot name="actions">
        <a href="{{ route('management.teams.index') }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
        <a href="{{ route('management.teams.edit', $team) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-fill me-1"></i> Editar Equipe
        </a>
    </x-slot>
</x-ui.page-context-header>