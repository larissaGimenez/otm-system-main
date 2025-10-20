@props([
    'area',
    'availableTeams',
])

<x-ui.form-modal
    id="addTeamModal"
    title="Adicionar Equipes à Área"
    :formAction="route('areas.teams.attach', $area)"
    formMethod="POST"
>
    @if($availableTeams->isNotEmpty())
        <p>Selecione uma ou mais equipes para adicionar à área <strong>{{ $area->name }}</strong>.</p>
        <select name="teams[]" multiple placeholder="Selecione as equipes..." x-init="new TomSelect($el, { plugins: ['remove_button'] })">
            @foreach ($availableTeams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
            @endforeach
        </select>
    @else
        <p class="text-muted text-center">Todas as equipes já pertencem a uma área.</p>
    @endif

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
        @if ($availableTeams->isNotEmpty())
            <button type="submit" :disabled="submitting" class="btn btn-primary d-inline-flex align-items-center">
                <span x-show="submitting" class="spinner-border spinner-border-sm me-2" role="status"></span>
                <span x-text="submitting ? 'Adicionando...' : 'Adicionar'"></span>
            </button>
        @endif
    </x-slot>
</x-ui.form-modal>