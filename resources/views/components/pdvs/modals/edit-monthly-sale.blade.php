@props(['sale'])

<x-ui.form-modal 
    id="editMonthlySaleModal_{{ $sale->id }}"
    title="Editar Faturamento ({{ str_pad($sale->month, 2, '0', STR_PAD_LEFT) }}/{{ $sale->year }})"
    formAction="{{ route('monthly-sales.update', $sale) }}"
    formMethod="PUT"
>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="number" class="form-control" id="year_{{ $sale->id }}" name="year" value="{{ old('year', $sale->year) }}" placeholder="Ano" required>
                <label for="year_{{ $sale->id }}">Ano*</label>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="number" class="form-control" id="month_{{ $sale->id }}" name="month" value="{{ old('month', $sale->month) }}" placeholder="Mês" required>
                <label for="month_{{ $sale->id }}">Mês*</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="number" step="0.01" class="form-control" id="gross_sales_value_{{ $sale->id }}" name="gross_sales_value" value="{{ old('gross_sales_value', $sale->gross_sales_value) }}" placeholder="1000.00" required>
                <label for="gross_sales_value_{{ $sale->id }}">Venda Bruta (R$)*</label>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="number" step="0.01" class="form-control" id="net_sales_value_{{ $sale->id }}" name="net_sales_value" value="{{ old('net_sales_value', $sale->net_sales_value) }}" placeholder="800.00">
                <label for="net_sales_value_{{ $sale->id }}">Venda Líquida (R$)</label>
            </div>
        </div>
    </div>
</x-ui.form-modal>