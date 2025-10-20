@props([
    'ext', // O objeto ExternalId
    'pdv',
])

<x-ui.form-modal
    id="extidEditModal_{{ $ext->id }}"
    title="Editar ID Externo"
    :formAction="route('external-ids.update', $ext)"
    formMethod="PUT"
    submitText="Salvar Alterações"
>
    <input type="hidden" name="item_uuid" value="{{ $pdv->id }}">
    <input type="hidden" name="item_name" value="{{ $pdv->name }}">

    <div class="mb-3">
        <label class="form-label small">Sistema externo</label>
        <input type="text" class="form-control form-control-sm" name="system_name" value="{{ $ext->system_name }}" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label small">ID externo</label>
        <input type="text" class="form-control form-control-sm" name="external_id" value="{{ $ext->external_id }}" required>
    </div>
</x-ui.form-modal>