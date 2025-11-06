@props(['request', 'availableAssignees'])

@php
    $filteredAssignees = $availableAssignees->whereNotIn('id', $request->assignees->pluck('id'));
    $currentAssignees = $availableAssignees->whereIn('id', $request->assignees->pluck('id'));
@endphp

{{-- Usamos x-data para o Alpine.js --}}
<div x-data="{ 
    selected: {{ $request->assignees->pluck('id') }},
    count() { return this.selected.length }
}">
    <x-ui.form-modal 
        id="assignUsersModal_{{ $request->id }}"
        title="Atribuir Responsáveis"
        formAction="{{ route('requests.assignees.attach', $request) }}"
    >
        <p class="text-muted small">Selecione um ou mais membros para este chamado.</p>

        @if($filteredAssignees->isEmpty() && $currentAssignees->isEmpty())
             <p class="text-muted small">Não há usuários disponíveis na área deste chamado.</p>
        @endif

        {{-- Lista de checkboxes --}}
        <div class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
            
            {{-- 1. Usuários já atribuídos (marcados) --}}
            @foreach ($currentAssignees as $user)
            <label class="list-group-item list-group-item-action d-flex align-items-center">
                <input 
                    class="form-check-input me-3" 
                    type="checkbox" 
                    name="assignees[]" 
                    value="{{ $user->id }}"
                    x-model="selected"
                >
                {{ $user->name }}
            </label>
            @endforeach

            {{-- 2. Usuários disponíveis (não marcados) --}}
            @foreach ($filteredAssignees as $user)
            <label class="list-group-item list-group-item-action d-flex align-items-center">
                <input 
                    class="form-check-input me-3" 
                    type="checkbox" 
                    name="assignees[]" 
                    value="{{ $user->id }}"
                    x-model="selected"
                >
                {{ $user->name }}
            </label>
            @endforeach
        </div>
        
        @error('assignees') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

        {{-- Rodapé customizado (como na imagem de referência) --}}
        <x-slot:footer>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" :disabled="count() === 0">
                Atribuir (<span x-text="count()"></span>)
            </button>
        </x-slot:footer>
    </x-ui.form-modal>
</div>