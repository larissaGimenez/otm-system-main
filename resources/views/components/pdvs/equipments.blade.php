@props([
    'pdv',
])

@php
    $columns = [
        'EQUIPAMENTO',
        'TIPO',
        'ASSOCIADO EM',
    ];
@endphp

<x-ui.item-association-panel
    title="Equipamentos Associados"
    buttonText="Associar Equipamento"
    modalTargetId="#addEquipmentModal"
    :records="$pdv->equipments"
    :columns="$columns"
    emptyStateMessage="Nenhum equipamento associado a este PDV."
>
    @foreach ($pdv->equipments as $equipment)
        <tr>
            <td>
                <a href="{{ route('equipments.show', $equipment) }}" class="text-decoration-none text-dark fw-bold">
                    {{ $equipment->name }}
                </a>
            </td>
            <td>{{ $equipment->type->name ?? 'Sem tipo' }}</td>
            <td>{{ $equipment->pivot->created_at->format('d/m/Y') }}</td>
            <td class="text-end">
                @php
                    $equipmentName = e($equipment->name);
                @endphp
                
                {{-- A SOLUÇÃO ESTÁ AQUI --}}
                {{-- Criamos um formulário direto com um alerta de confirmação no onsubmit --}}
                <form method="POST" action="{{ route('pdvs.equipments.detach', ['pdv' => $pdv, 'equipment' => $equipment]) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja desassociar o equipamento \'{{ $equipmentName }}\'?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Desassociar equipamento">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</x-ui.item-association-panel>