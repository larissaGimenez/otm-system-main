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
    <input type="hidden" name="item_id" value="{{ $pdv->id }}">
    <input type="hidden" name="item_type" value="{{ addslashes(\App\Models\Pdv::class) }}">

    <div class="mb-3">
        <label class="form-label small">Sistema externo</label>
        <input type="text" name="system_name" class="form-control form-control-sm"
               placeholder="Ex: ERP-X" required>
    </div>

    <div class="mb-3">
        <label class="form-label small">ID externo</label>
        <input type="text" name="external_id" class="form-control form-control-sm"
               placeholder="Informe o ID no sistema externo" required>
    </div>
</x-ui.form-modal>
