{{-- resources/views/components/clients/modals/create-contract.blade.php --}}
@props(['client'])

<x-ui.form-modal 
    id="createContractModal_{{ $client->id }}"
    title="Adicionar Novo Contrato"
    formAction="{{ route('clients.contracts.store', $client) }}"
    size="lg"
    enctype="multipart/form-data"
>

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
    
    <div x-data="{ has_fee: false, has_commission: false }">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="form-floating">
                    <input
                        type="date"
                        class="form-control"
                        id="signed_at_{{ $client->id }}"
                        name="signed_at"
                        value="{{ old('signed_at', now()->format('Y-m-d')) }}"
                        required
                    >
                    <label for="signed_at_{{ $client->id }}">Data Assinatura*</label>
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
                id="has_monthly_fee_{{ $client->id }}"
                name="has_monthly_fee"
                value="1"
                x-model="has_fee"
            >
            <label class="form-check-label" for="has_monthly_fee_{{ $client->id }}">
                Contrato possui mensalidade fixa?
            </label>
        </div>

        <div class="row" x-show="has_fee" style="display: none;">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input
                        type="number"
                        step="0.01"
                        class="form-control"
                        id="monthly_fee_value_{{ $client->id }}"
                        name="monthly_fee_value"
                        placeholder="150.00"
                    >
                    <label for="monthly_fee_value_{{ $client->id }}">Valor da Mensalidade (R$)</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input
                        type="number"
                        class="form-control"
                        id="monthly_fee_due_day_{{ $client->id }}"
                        name="monthly_fee_due_day"
                        placeholder="10"
                        min="1"
                        max="31"
                    >
                    <label for="monthly_fee_due_day_{{ $client->id }}">Dia do Vencimento (1-31)</label>
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
                id="has_commission_{{ $client->id }}"
                name="has_commission"
                value="1"
                x-model="has_commission"
            >
            <label class="form-check-label" for="has_commission_{{ $client->id }}">
                Contrato possui repasse de vendas?
            </label>
        </div>

        <div x-show="has_commission" style="display: none;">
            <div class="form-floating mb-3">
                <input
                    type="number"
                    step="0.01"
                    class="form-control"
                    id="commission_percentage_{{ $client->id }}"
                    name="commission_percentage"
                    placeholder="15.5"
                >
                <label for="commission_percentage_{{ $client->id }}">Percentual de Repasse (%)</label>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-2">Anexo (PDF)</h6>
        <div class="mb-3">
            <label for="pdf_file_{{ $client->id }}" class="form-label">Anexar Contrato (PDF)</label>
            <input 
                class="form-control" 
                type="file" 
                id="pdf_file_{{ $client->id }}" 
                name="pdf_file"
                accept=".pdf"
            >
        </div>
    </div>
</x-ui.form-modal>
