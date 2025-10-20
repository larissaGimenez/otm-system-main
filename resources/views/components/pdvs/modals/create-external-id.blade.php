@props([
    'pdv',
    'modalId',
])

<x-ui.form-modal
    :id="$modalId"
    title="Adicionar ID Externo"
    :formAction="route('external-ids.store')"
    submitText="Adicionar"
>
    {{-- Inputs ocultos necessários para a lógica do controller --}}
    <input type="hidden" name="item_uuid" value="{{ $pdv->id }}">
    <input type="hidden" name="item_name" value="{{ $pdv->name }}">

    <div class="mb-3">
        <label class="form-label small">Sistema externo</label>
        <input type="text" name="system_name" class="form-control form-control-sm"
               value="{{ old('system_name') }}" placeholder="Ex: ERP-X" required>
    </div>

    <div class="mb-3">
        <label class="form-label small">ID externo</label>
        <input type="text" name="external_id" class="form-control form-control-sm"
               value="{{ old('external_id') }}" placeholder="Informe o ID no sistema externo" required>
    </div>

    {{-- Feedback de validação (importante para a experiência do usuário) --}}
    @if ($errors->any() && old('item_uuid') === $pdv->id)
        <div class="alert alert-danger small p-2 mb-0">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</x-ui.form-modal>