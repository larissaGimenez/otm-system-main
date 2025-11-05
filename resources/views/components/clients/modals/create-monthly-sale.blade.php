@props(['contract'])

<x-ui.form-modal 
    id="createMonthlySaleModal_{{ $contract->id }}"
    title="Registrar Faturamento"
    formAction="{{ route('contracts.monthly-sales.store', $contract) }}"
>
    <p class="small text-muted">
        Você está registrando um faturamento para o contrato assinado em
        <strong>{{ optional($contract->signed_at)->format('d/m/Y') ?? '—' }}</strong>.
    </p>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input
                    type="number"
                    class="form-control"
                    id="year_{{ $contract->id }}"
                    name="year"
                    value="{{ old('year', now()->year) }}"
                    placeholder="Ano"
                    required
                    min="2000"
                    max="2100"
                    onwheel="this.blur()"
                >
                <label for="year_{{ $contract->id }}">Ano*</label>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input
                    type="number"
                    class="form-control"
                    id="month_{{ $contract->id }}"
                    name="month"
                    value="{{ old('month', now()->month) }}"
                    placeholder="Mês"
                    required
                    min="1"
                    max="12"
                    onwheel="this.blur()"
                >
                <label for="month_{{ $contract->id }}">Mês* (1–12)</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input
                    type="number"
                    step="0.01"
                    class="form-control"
                    id="gross_sales_value_{{ $contract->id }}"
                    name="gross_sales_value"
                    value="{{ old('gross_sales_value') }}"
                    placeholder="1000.00"
                    required
                    min="0"
                    onwheel="this.blur()"
                >
                <label for="gross_sales_value_{{ $contract->id }}">Venda Bruta (R$)*</label>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input
                    type="number"
                    step="0.01"
                    class="form-control"
                    id="net_sales_value_{{ $contract->id }}"
                    name="net_sales_value"
                    value="{{ old('net_sales_value') }}"
                    placeholder="800.00"
                    min="0"
                    onwheel="this.blur()"
                >
                <label for="net_sales_value_{{ $contract->id }}">Venda Líquida (R$)</label>
            </div>
        </div>
    </div>
</x-ui.form-modal>
