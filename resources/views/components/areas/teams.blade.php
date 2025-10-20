@props(['area'])

<x-ui.item-association-panel
    title="Equipes Associadas"
    buttonText="Adicionar Equipe"
    modalTargetId="#addTeamModal"
    :records="$area->teams"
    :columns="['NOME DA EQUIPE', 'STATUS', 'MEMBROS']"
    emptyStateMessage="Nenhuma equipe associada a esta área."
>
    @foreach ($area->teams as $team)
        <tr>
            <td>
                <a href="{{ route('management.teams.show', $team) }}" class="text-decoration-none text-dark fw-bold">
                    {{ $team->name }}
                </a>
            </td>
            <td>
                <span class="badge rounded-pill {{ $team->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                    {{ ucfirst($team->status) }}
                </span>
            </td>
            <td>{{ $team->users_count ?? $team->users()->count() }}</td>
            <td class="text-end">
                <form method="POST" action="{{ route('areas.teams.detach', ['area' => $area, 'team' => $team]) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja desassociar a equipe \'{{ e($team->name) }}\' desta área?')">
                    @csrf
                    @method('PATCH') {{-- Usamos PATCH para "atualizar" a equipe, removendo a associação --}}
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desassociar Equipe">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</x-ui.item-association-panel>