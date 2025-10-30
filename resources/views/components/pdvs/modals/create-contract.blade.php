@props(['pdv'])

{{-- Usamos o 'form-modal' que você me enviou --}}
<x-ui.form-modal 
    id="createContractModal_{{ $pdv->id }}"
    title="Adicionar Novo Contrato"
    formAction="{{ route('pdvs.contracts.store', $pdv) }}"
    size="lg"
>
    <div x-data="{ has_fee: false, has_commission: false }">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="form-floating">
                    <input type="date" class="form-control" id="signed_at" name="signed_at" value="{{ old('signed_at', now()->format('Y-m-d')) }}" required>
                    <label for="signed_at">Data Assinatura*</label>
                </div>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-2">Mensalidade</h6>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" role="switch" id="has_monthly_fee" name="has_monthly_fee" value="1" x-model="has_fee">
            <label class="form-check-label" for="has_monthly_fee">Contrato possui mensalidade fixa?</label>
        </div>
        
        <div class="row" x-show="has_fee" style="display: none;">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="number" step="0.01" class="form-control" id="monthly_fee_value" name="monthly_fee_value" placeholder="150.00">
                    <label for="monthly_fee_value">Valor da Mensalidade (R$)</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="number" class="form-control" id="monthly_fee_due_day" name="monthly_fee_due_day" placeholder="10">
                    <label for="monthly_fee_due_day">Dia do Vencimento (1-31)</label>
                </div>
            </div>
        </div>
        
        <h6 class="card-title text-muted small text-uppercase mt-2">Repasse (Comissão)</h6>
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" role="switch" id="has_commission" name="has_commission" value="1" x-model="has_commission">
            <label class="form-check-label" for="has_commission">Contrato possui repasse de vendas?</label>
        </div>

        <div x-show="has_commission" style="display: none;">
            <div class="form-floating mb-3">
                <input type="number" step="0.01" class="form-control" id="commission_percentage" name="commission_percentage" placeholder="15.5">
                <label for="commission_percentage">Percentual de Repasse (%)</label>
            </div>
        </div>

        <h6 class="card-title text-muted small text-uppercase mt-2">Dados de Pagamento (Opcional)</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="payment_bank_name" name="payment_bank_name" placeholder="Ex: Banco do Brasil">
                    <label for="payment_bank_name">Banco</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="payment_pix_key" name="payment_pix_key" placeholder="CNPJ, E-mail, etc">
                    <label for="payment_pix_key">Chave PIX</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="payment_bank_agency" name="payment_bank_agency" placeholder="0001">
                    <label for="payment_bank_agency">Agência</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="payment_bank_account" name="payment_bank_account" placeholder="12345-6">
                    <label for="payment_bank_account">Conta</label>
                </div>
            </div>
        </div>
    </div>
</x-ui.form-modal>