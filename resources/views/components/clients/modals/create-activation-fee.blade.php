@props(['client'])

<x-ui.form-modal 
    id="createActivationFeeModal_{{ $client->id }}"
    title="Configurar Custo de Implantação"
    formAction="{{ route('clients.activation-fee.store', $client) }}"
    size="lg"
>
    <div x-data="{ installments: 1 }">
        <p class="small text-muted">
            Informe o valor total, o número de parcelas e a data do primeiro vencimento.
            O sistema irá gerar as parcelas automaticamente.
        </p>
        
        <h6 class="card-title text-muted small text-uppercase mt-3">Valores</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input 
                        type="number"
                        step="0.01"
                        class="form-control"
                        id="total_value"
                        name="total_value"
                        value="{{ old('total_value') }}"
                        placeholder="1200.00"
                        required
                    >
                    <label for="total_value">Valor Total (R$)*</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input 
                        type="number"
                        class="form-control"
                        id="installments_count"
                        name="installments_count"
                        value="{{ old('installments_count', 1) }}"
                        placeholder="1"
                        x-model.number="installments"
                        min="1"
                        required
                    >
                    <label for="installments_count">Nº de Parcelas*</label>
                </div>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-3">Vencimentos</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input 
                        type="date"
                        class="form-control"
                        id="first_due_date"
                        name="first_due_date"
                        value="{{ old('first_due_date', now()->format('Y-m-d')) }}"
                        required
                    >
                    <label for="first_due_date"
                           x-text="installments > 1 ? 'Data Venc. 1ª Parcela*' : 'Data de Vencimento*'">
                    </label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input 
                        type="date"
                        class="form-control"
                        id="last_due_date"
                        name="last_due_date"
                        value="{{ old('last_due_date') }}"
                    >
                    <label for="last_due_date">Data Venc. Última Parcela</label>
                </div>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-3">Observações</h6>
        <div class="row">
            <div class="col-12 mb-3">
                <div class="form-floating">
                    <textarea
                        class="form-control"
                        id="notes"
                        name="notes"
                        style="height: 100px"
                        placeholder="Observações"
                    >{{ old('notes') }}</textarea>
                    <label for="notes">Observações</label>
                </div>
            </div>
        </div>
    </div>
</x-ui.form-modal>
