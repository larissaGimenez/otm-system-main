@props(['fee'])

<x-ui.form-modal 
    id="editActivationFeeModal_{{ $fee->id }}"
    title="Editar Custo de Implantação"
    formAction="{{ route('activation-fee.update', $fee) }}"
    formMethod="PUT"
    size="lg"
>
    <p class="small text-muted">Atenção: A alteração destes dados não afeta as parcelas já geradas. Para alterar parcelas, utilize a tela de detalhes.</p>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <select class="form-select" id="payment_method_{{ $fee->id }}" name="payment_method" required>
                    @foreach(App\Enums\Pdv\FeePaymentMethod::cases() as $method)
                        <option value="{{ $method->value }}" @selected(old('payment_method', $fee->payment_method) == $method)>
                            {{ $method->getLabel() }}
                        </option>
                    @endforeach
                </select>
                <label for="payment_method_{{ $fee->id }}">Forma de Pagamento*</label>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-floating">
                <input type="date" class="form-control" id="due_date_{{ $fee->id }}" name="due_date" value="{{ old('due_date', $fee->due_date?->format('Y-m-d')) }}">
                <label for="due_date_{{ $fee->id }}">Vencimento Geral (Monitoramento)</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="form-floating">
                <textarea class="form-control" id="notes_{{ $fee->id }}" name="notes" style="height: 100px" placeholder="Obs">{{ old('notes', $fee->notes) }}</textarea>
                <label for="notes_{{ $fee->id }}">Observações</label>
            </div>
        </div>
    </div>
</x-ui.form-modal>