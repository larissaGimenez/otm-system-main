@props([
    'pdv',
])

@php
    try {
        $records = \App\Models\ExternalId::forItem($pdv->id)->latest()->get();
    } catch (\Throwable $e) {
        $records = collect();
    }
    
    $createModalId = "extidCreateModal_" . substr((string) $pdv->id, 0, 8);

    $columns = [
        ['label' => 'Sistema'],
        ['label' => 'ID Externo'],
        ['label' => 'Criado em'],
    ];
@endphp

<x-ui.crud-panel
    title="IDs Externos"
    buttonText="Adicionar ID Externo"
    :createModalTargetId="$createModalId"
    :records="$records"
    :columns="$columns"
    emptyStateMessage="Nenhum ID externo cadastrado para este item."
>
    {{-- AQUI ESTÁ A CORREÇÃO: Nós construímos o conteúdo da tabela aqui --}}
    @foreach ($records as $record)
        <tr>
            <td>{{ $record->system_name }}</td>
            <td><code class="small">{{ $record->external_id }}</code></td>
            <td>{{ $record->created_at->format('d/m/Y H:i') }}</td>
            <td class="py-2 text-end">
                @php
                    $editModalId = "extidEditModal_{$record->id}";
                @endphp
                
                <button type="button" class="btn btn-outline-primary btn-sm me-1"
                        data-bs-toggle="modal" data-bs-target="#{{ $editModalId }}">
                    <i class="bi bi-pencil"></i>
                </button>
                
                <form method="POST" action="{{ route('external-ids.destroy', $record) }}" class="d-inline" onsubmit="return confirm('Remover este ID externo?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
</x-ui.crud-panel>