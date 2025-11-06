{{-- resources/views/components/clients/modals/edit-contract.blade.php --}}
@props(['contract'])

<x-ui.form-modal 
    id="editContractModal_{{ $contract->id }}"
    title="Editar Contrato"
    formAction="{{ route('contracts.update', $contract) }}"
    formMethod="PUT"
    size="lg"
    enctype="multipart/form-data"
>
    <div
        x-data="{
            has_fee: {{ old('has_monthly_fee', $contract->has_monthly_fee) ? 'true' : 'false' }},
            has_commission: {{ old('has_commission', $contract->has_commission) ? 'true' : 'false' }},
        }"
    >
        <div class="row">
            <div class="col-12 mb-3">
                <div class="form-floating">
                    <input
                        type="date"
                        class="form-control"
                        id="signed_at_{{ $contract->id }}"
                        name="signed_at"
                        value="{{ old('signed_at', optional($contract->signed_at)->format('Y-m-d')) }}"
                        required
                    >
                    <label for="signed_at_{{ $contract->id }}">Data Assinatura*</label>
                </div>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-2">Mensalidade</h6>
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="has_monthly_fee" value="0">
            <input
                class="form-check-input"
                type="checkbox"
                role="switch"
                id="has_monthly_fee_{{ $contract->id }}"
                name="has_monthly_fee"
                value="1"
                x-model="has_fee"
                @checked(old('has_monthly_fee', $contract->has_monthly_fee))
            >
            <label class="form-check-label" for="has_monthly_fee_{{ $contract->id }}">
                Contrato possui mensalidade fixa?
            </label>
        </div>

        <div class="row" x-show="has_fee" @if(!old('has_monthly_fee', $contract->has_monthly_fee)) style="display: none;" @endif>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input
                        type="number"
                        step="0.01"
                        class="form-control"
                        id="monthly_fee_value_{{ $contract->id }}"
                        name="monthly_fee_value"
                        value="{{ old('monthly_fee_value', $contract->monthly_fee_value) }}"
                        placeholder="150.00"
                    >
                    <label for="monthly_fee_value_{{ $contract->id }}">Valor da Mensalidade (R$)</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input
                        type="number"
                        class="form-control"
                        id="monthly_fee_due_day_{{ $contract->id }}"
                        name="monthly_fee_due_day"
                        value="{{ old('monthly_fee_due_day', $contract->monthly_fee_due_day) }}"
                        placeholder="10"
                        min="1"
                        max="31"
                    >
                    <label for="monthly_fee_due_day_{{ $contract->id }}">Dia do Vencimento (1-31)</label>
                </div>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-2">Repasse (Comissão)</h6>
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="has_commission" value="0">
            <input
                class="form-check-input"
                type="checkbox"
                role="switch"
                id="has_commission_{{ $contract->id }}"
                name="has_commission"
                value="1"
                x-model="has_commission"
                @checked(old('has_commission', $contract->has_commission))
            >
            <label class="form-check-label" for="has_commission_{{ $contract->id }}">
                Contrato possui repasse de vendas?
            </label>
        </div>

        <div x-show="has_commission" @if(!old('has_commission', $contract->has_commission)) style="display: none;" @endif>
            <div class="form-floating mb-3">
                <input
                    type="number"
                    step="0.01"
                    class="form-control"
                    id="commission_percentage_{{ $contract->id }}"
                    name="commission_percentage"
                    value="{{ old('commission_percentage', $contract->commission_percentage) }}"
                    placeholder="15.5"
                >
                <label for="commission_percentage_{{ $contract->id }}">Percentual de Repasse (%)</label>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-2">Anexo (PDF)</h6>
        <div class="mb-3">
            <label for="pdf_file_{{ $contract->id }}" class="form-label">Substituir Anexo (PDF)</label>
            <input 
                class="form-control" 
                type="file" 
                id="pdf_file_{{ $contract->id }}" 
                name="pdf_file"
                accept=".pdf"
            >
            @if ($contract->pdf_path)
                <div class="form-text mt-2">
                    <a href="{{ asset('storage/' . $contract->pdf_path) }}" target="_blank">
                        <i class="bi bi-file-earmark-pdf"></i> 
                        Ver anexo atual
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-ui.form-modal>
