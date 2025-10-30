@props(['contract'])

<x-ui.form-modal 
    id="editContractModal_{{ $contract->id }}"
    title="Editar Contrato"
    formAction="{{ route('contracts.update', $contract) }}"
    formMethod="PUT"
    size="lg"
>
    {{-- Inicializa o Alpine com os dados do contrato --}}
    <div x-data="{ 
        has_fee: {{ old('has_monthly_fee', $contract->has_monthly_fee) ? 'true' : 'false' }}, 
        has_commission: {{ old('has_commission', $contract->has_commission) ? 'true' : 'false' }} 
    }">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="form-floating">
                    <input type="date" class="form-control" id="signed_at_{{ $contract->id }}" name="signed_at" value="{{ old('signed_at', $contract->signed_at->format('Y-m-d')) }}" required>
                    <label for="signed_at_{{ $contract->id }}">Data Assinatura*</label>
                </div>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-2">Mensalidade</h6>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" role="switch" id="has_monthly_fee_{{ $contract->id }}" name="has_monthly_fee" value="1" x-model="has_fee" @checked(old('has_monthly_fee', $contract->has_monthly_fee))>
            <label class="form-check-label" for="has_monthly_fee_{{ $contract->id }}">Contrato possui mensalidade fixa?</label>
        </div>
        
        <div class="row" x-show="has_fee" @if(!old('has_monthly_fee', $contract->has_monthly_fee)) style="display: none;" @endif>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="monthly_fee_value_{{ $contract->id }}" name="monthly_fee_value" value="{{ old('monthly_fee_value', $contract->monthly_fee_value) }}" placeholder="150.00">
                    <label for="monthly_fee_value_{{ $contract->id }}">Valor da Mensalidade (R$)</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="number" class="form-control" id="monthly_fee_due_day_{{ $contract->id }}" name="monthly_fee_due_day" value="{{ old('monthly_fee_due_day', $contract->monthly_fee_due_day) }}" placeholder="10">
                    <label for="monthly_fee_due_day_{{ $contract->id }}">Dia do Vencimento (1-31)</label>
                </div>
            </div>
        </div>
        
        <h6 class="card-title text-muted small text-uppercase mt-2">Repasse (Comissão)</h6>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" role="switch" id="has_commission_{{ $contract->id }}" name="has_commission" value="1" x-model="has_commission" @checked(old('has_commission', $contract->has_commission))>
            <label class="form-check-label" for="has_commission_{{ $contract->id }}">Contrato possui repasse de vendas?</label>
        </div>

        <div x-show="has_commission" @if(!old('has_commission', $contract->has_commission)) style="display: none;" @endif>
            <div class="form-floating mb-3">
                <input type="number" step="0.01" class="form-control" id="commission_percentage_{{ $contract->id }}" name="commission_percentage" value="{{ old('commission_percentage', $contract->commission_percentage) }}" placeholder="15.5">
                <label for="commission_percentage_{{ $contract->id }}">Percentual de Repasse (%)</label>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-2">Dados de Pagamento (Opcional)</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="payment_bank_name_{{ $contract->id }}" name="payment_bank_name" value="{{ old('payment_bank_name', $contract->payment_bank_name) }}" placeholder="Ex: Banco do Brasil">
                    <label for="payment_bank_name_{{ $contract->id }}">Banco</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="payment_pix_key_{{ $contract->id }}" name="payment_pix_key" value="{{ old('payment_pix_key', $contract->payment_pix_key) }}" placeholder="CNPJ, E-mail, etc">
                    <label for="payment_pix_key_{{ $contract->id }}">Chave PIX</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="payment_bank_agency_{{ $contract->id }}" name="payment_bank_agency" value="{{ old('payment_bank_agency', $contract->payment_bank_agency) }}" placeholder="0001">
                    <label for="payment_bank_agency_{{ $contract->id }}">Agência</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="payment_bank_account_{{ $contract->id }}" name="payment_bank_account" value="{{ old('payment_bank_account', $contract->payment_bank_account) }}" placeholder="12345-6">
                    <label for="payment_bank_account_{{ $contract->id }}">Conta</label>
                </div>
            </div>
        </div>
    </div>
</x-ui.form-modal>