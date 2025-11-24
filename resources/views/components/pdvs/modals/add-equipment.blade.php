@props([
    'pdv',
    'availableEquipments',
])

{{-- O formulário fica aqui, envolvendo a chamada do componente de modal --}}
<form x-data="{ submitting: false }" @submit="submitting = true" action="{{ route('pdvs.equipments.attach', $pdv) }}" method="POST">
    @csrf
    <x-ui.modal id="addEquipmentModal" title="Associar Equipamentos">
        
        {{-- Conteúdo do corpo do modal (slot padrão) --}}
        @if($availableEquipments->isNotEmpty())
            <p>Selecione um ou mais equipamentos para associar a <strong>{{ $pdv->name }}</strong>.</p>
            
            <select 
                id="select-equipments-attach"
                name="equipments[]"
                multiple
                placeholder="Selecione os equipamentos..."
                {{-- ADICIONE ESTA LINHA --}}
                x-init="new TomSelect($el, { plugins: ['remove_button'] })"
            >
                @foreach ($availableEquipments as $equipment)
                    <option value="{{ $equipment->id }}">
                        {{ $equipment->name }} ({{ $equipment->type->name ?? 'Sem tipo' }})
                    </option>
                @endforeach
            </select>
        @else
            <p class="text-muted text-center">Todos os equipamentos já estão associados a este PDV ou não há equipamentos cadastrados.</p>
        @endif

        {{-- Conteúdo do rodapé do modal (slot 'footer') --}}
        <x-slot name="footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            @if($availableEquipments->isNotEmpty())
                {{-- 2. Modificamos o botão de submit --}}
                <button type="submit" :disabled="submitting" class="btn btn-primary d-inline-flex align-items-center">
                    <span x-show="submitting" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    <span x-text="submitting ? 'Associando...' : 'Associar'"></span>
                </button>
            @endif
        </x-slot>

    </x-ui.modal>
</form>