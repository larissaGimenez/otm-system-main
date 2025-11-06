@props(['contact'])

@php
    $types = \App\Enums\Contact\ContactType::cases();
@endphp

<x-ui.form-modal 
    id="editContactModal_{{ $contact->id }}"
    title="Editar Contato"
    formAction="{{ route('contacts.update', $contact) }}"
    formMethod="PUT"
>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="name_edit_{{ $contact->id }}" name="name" value="{{ old('name', $contact->name) }}" placeholder="Nome do Contato" required>
                <label for="name_edit_{{ $contact->id }}">Nome*</label>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <select class="form-select" id="type_edit_{{ $contact->id }}" name="type" required>
                    <option value="" disabled>Selecione um tipo...</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}" @selected(old('type', $contact->type) == $type)>
                            {{ $type->getLabel() }}
                        </option>
                    @endforeach
                </select>
                <label for="type_edit_{{ $contact->id }}">Tipo*</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="form-floating">
                <input type="email" class="form-control" id="email_edit_{{ $contact->id }}" name="email" value="{{ old('email', $contact->email) }}" placeholder="email@exemplo.com">
                <label for="email_edit_{{ $contact->id }}">Email</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="tel" class="form-control" id="phone_primary_edit_{{ $contact->id }}" name="phone_primary" value="{{ old('phone_primary', $contact->phone_primary) }}" placeholder="Telefone Principal">
                <label for="phone_primary_edit_{{ $contact->id }}">Telefone Principal</label>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="tel" class="form-control" id="phone_secondary_edit_{{ $contact->id }}" name="phone_secondary" value="{{ old('phone_secondary', $contact->phone_secondary) }}" placeholder="Telefone Secundário">
                <label for="phone_secondary_edit_{{ $contact->id }}">Telefone Secundário</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-floating">
                <textarea class="form-control" id="notes_edit_{{ $contact->id }}" name="notes" placeholder="Observações" style="height: 100px">{{ old('notes', $contact->notes) }}</textarea>
                <label for="notes_edit_{{ $contact->id }}">Observações</label>
            </div>
        </div>
    </div>
</x-ui.form-modal>