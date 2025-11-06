@props(['client'])

@php
    $contacts = $client->contacts ?? collect();
    $columns = [
        ['label' => 'Nome'],
        ['label' => 'Tipo'],
        ['label' => 'Email'],
        ['label' => 'Telefone'],
    ];
@endphp

<x-ui.crud-panel
    title="Contatos"
    buttonText="Adicionar Contato"
    createModalTargetId="createContactModal_{{ $client->id }}"
    :records="$contacts"
    :columns="$columns"
    emptyStateMessage="Nenhum contato cadastrado para este cliente."
>
    @foreach ($contacts as $contact)
        <tr>
            <td>
                <strong>{{ $contact->name }}</strong>
            </td>
            <td>
                <span class="badge bg-secondary">{{ $contact->type->getLabel() }}</span>
            </td>
            <td>
                {{ $contact->email ?? '—' }}
            </td>
            <td>
                {{ $contact->phone_primary ?? '—' }}
            </td>
            <td class="text-end">
                {{-- Botão Editar --}}
                <button type="button"
                        class="btn btn-outline-secondary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#editContactModal_{{ $contact->id }}">
                    <i class="bi bi-pencil"></i>
                </button>

                {{-- Botão Excluir --}}
                <form action="{{ route('contacts.destroy', $contact) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Tem certeza que deseja excluir este contato?');">
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