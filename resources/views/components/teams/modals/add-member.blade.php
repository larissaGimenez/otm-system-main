@props([
    'team',
    'availableUsers',
])

{{-- 
    NÃO precisamos de uma tag <form> aqui. O componente form-modal já cuida disso.
    Nós passamos a action e outras props diretamente para ele.
--}}
<x-ui.form-modal
    id="addMemberModal"
    title="Adicionar Membros à Equipe"
    :formAction="route('management.teams.users.attach', $team)"
>
    {{-- O conteúdo do corpo não muda --}}
    @if($availableUsers->isNotEmpty())
        <p>Selecione um ou mais usuários para adicionar à equipe <strong>{{ $team->name }}</strong>.</p>
        <select
            id="select-users-attach"
            name="users[]"
            multiple
            placeholder="Selecione os usuários..."
            x-init="new TomSelect($el, { plugins: ['remove_button'] })"
        >
            @foreach ($availableUsers as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
            @endforeach
        </select>
    @else
        <p class="text-muted text-center">Não existem usuários disponíveis para adicionar.</p>
    @endif

    {{-- A lógica do footer customizado continua a mesma e está correta --}}
    <x-slot name="footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>

        {{-- O botão de Adicionar só aparece se houver usuários disponíveis --}}
        @if ($availableUsers->isNotEmpty())
            {{-- A lógica de desabilitar o botão é herdada do form-modal --}}
            <button type="submit" :disabled="submitting" class="btn btn-primary d-inline-flex align-items-center">
                <span x-show="submitting" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                <span x-text="submitting ? 'Adicionando...' : 'Adicionar'"></span>
            </button>
        @endif
    </x-slot>
</x-ui.form-modal>