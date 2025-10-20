@props(['team'])

@php
    $columns = [
        'NOME',
        'EMAIL',
        'ADICIONADO EM',
    ];
@endphp

<x-ui.item-association-panel
    title="Membros da Equipe"
    buttonText="Adicionar Membro"
    modalTargetId="#addMemberModal"
    :records="$team->users"
    :columns="$columns"
    emptyStateMessage="Nenhum membro associado a esta equipe."
>
    @foreach ($team->users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->pivot->created_at->format('d/m/Y') }}</td>
            <td class="text-end">
                @php
                    // Usamos e() para escapar o nome e evitar problemas de XSS no alerta
                    $userName = e($user->name);
                @endphp
                
                {{-- Usamos o formulário com alerta de confirmação, que é robusto e simples --}}
                <form method="POST" action="{{ route('management.teams.users.remove', ['team' => $team, 'user' => $user]) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja remover o membro \'{{ $userName }}\' desta equipe?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover membro">
                        <i class="bi bi-person-dash-fill"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</x-ui.item-association-panel>