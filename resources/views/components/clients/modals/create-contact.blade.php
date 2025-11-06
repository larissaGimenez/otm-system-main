@props(['client'])

@php
    $types = \App\Enums\Contact\ContactType::cases();
@endphp

<x-ui.form-modal 
    id="createContactModal_{{ $client->id }}"
    title="Adicionar Novo Contato"
    formAction="{{ route('clients.contacts.store', $client) }}"
>
    {{-- Bloco de Erros de Validação --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <h6 class="alert-heading">Opa! Algo deu errado:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="text" class="form-control" id="name_create_{{ $client->id }}" name="name" value="{{ old('name') }}" placeholder="Nome do Contato" required>
                <label for="name_create_{{ $client->id }}">Nome*</label>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <select class="form-select" id="type_create_{{ $client->id }}" name="type" required>
                    <option value="" disabled selected>Selecione um tipo...</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}" @selected(old('type') == $type->value)>
                            {{ $type->getLabel() }}
                        </option>
                    @endforeach
                </select>
                <label for="type_create_{{ $client->id }}">Tipo*</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="form-floating">
                <input type="email" class="form-control" id="email_create_{{ $client->id }}" name="email" value="{{ old('email') }}" placeholder="email@exemplo.com">
                <label for="email_create_{{ $client->id }}">Email</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="tel" class="form-control" id="phone_primary_create_{{ $client->id }}" name="phone_primary" value="{{ old('phone_primary') }}" placeholder="Telefone Principal">
                <label for="phone_primary_create_{{ $client->id }}">Telefone Principal</label>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="tel" class="form-control" id="phone_secondary_create_{{ $client->id }}" name="phone_secondary" value="{{ old('phone_secondary') }}" placeholder="Telefone Secundário">
                <label for="phone_secondary_create_{{ $client->id }}">Telefone Secundário</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-floating">
                <textarea class="form-control" id="notes_create_{{ $client->id }}" name="notes" placeholder="Observações" style="height: 100px">{{ old('notes') }}</textarea>
                <label for="notes_create_{{ $client->id }}">Observações</label>
            </div>
        </div>
    </div>
</x-ui.form-modal>