@props(['client', 'fee'])

<x-ui.form-modal
    id="editActivationFeeModal_{{ $client->id }}"
    title="Editar Custo de Implantação"
    formAction="{{ route('clients.activation-fee.update', $client) }}"
    formMethod="PUT"
    size="lg"
>
    <p class="small text-muted mb-3">
        Atenção: Alterar estes dados <strong>não</strong> altera as parcelas já geradas.
        Para modificar parcelas, use a lista de parcelas.
    </p>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input
                    type="number"
                    step="0.01"
                    class="form-control"
                    id="total_value_{{ $client->id }}"
                    name="total_value"
                    value="{{ old('total_value', $fee->total_value) }}"
                    placeholder="1200.00"
                    required
                >
                <label for="total_value_{{ $client->id }}">Valor Total (R$)*</label>
            </div>
        </div>

        <div class="col-12 mb-3">
            <div class="form-floating">
                <textarea
                    class="form-control"
                    id="notes_{{ $client->id }}"
                    name="notes"
                    style="height: 100px"
                    placeholder="Observações"
                >{{ old('notes', $fee->notes) }}</textarea>
                <label for="notes_{{ $client->id }}">Observações</label>
            </div>
        </div>
    </div>
</x-ui.form-modal>
