@props(['client'])

<x-ui.form-modal 
    id="createActivationFeeModal_{{ $client->id }}"
    title="Configurar Custo de Implantação"
    formAction="{{ route('clients.activation-fee.store', $client) }}"
    size="lg"
>
    <div
        x-data="createFee({
            initTotal: '{{ old('total_value') }}',
            initCount: {{ old('installments_count', 1) }},
            initFirst: '{{ old('first_due_date', now()->format('Y-m-d')) }}',
            oldInstallments: @json(old('installments', [])),
        })"
    >
        <p class="small text-muted">
            Informe o valor total, o número de parcelas e a data do primeiro vencimento.
            Você pode ajustar a data de cada parcela antes de salvar.
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
                        x-model.number="total"
                        placeholder="1200.00"
                        min="0"
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
                        x-model.number="count"
                        placeholder="1"
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
                        x-model="first"
                        required
                    >
                    <label for="first_due_date">Data Venc. 1ª Parcela*</label>
                </div>
            </div>
        </div>

        {{-- Pré-visualização / edição das parcelas --}}
        <template x-if="count > 0">
            <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="card-title text-muted small text-uppercase m-0">Pré-visualização das Parcelas</h6>
                    <div class="small text-muted">
                        Total: <strong x-text="formatBRL(total)"></strong>
                        &nbsp;•&nbsp; Soma das parcelas: <strong x-text="formatBRL(sumAmounts())"></strong>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width:90px">Parcela</th>
                                <th style="width:200px">Vencimento</th>
                                <th style="width:180px">Valor (R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(inst, idx) in installments" :key="idx">
                                <tr>
                                    <td>
                                        <strong x-text="inst.installment_number"></strong>
                                        <input type="hidden"
                                               :name="`installments[${idx}][installment_number]`"
                                               x-model.number="inst.installment_number">
                                    </td>
                                    <td>
                                        <input type="date"
                                               class="form-control form-control-sm"
                                               :name="`installments[${idx}][due_date]`"
                                               x-model="inst.due_date">
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">R$</span>
                                            <input type="number"
                                                   step="0.01"
                                                   min="0"
                                                   class="form-control"
                                                   :name="`installments[${idx}][amount]`"
                                                   x-model.number="inst.amount">
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small">
                    Dica: ajustamos automaticamente os centavos na última parcela. Você pode alterar datas e valores livremente, se necessário.
                </p>
            </div>
        </template>

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

    {{-- Alpine helpers --}}
    <script>
        function createFee({initTotal, initCount, initFirst, oldInstallments}) {
            return {
                total: initTotal ? parseFloat(initTotal) : 0,
                count: initCount || 1,
                first: initFirst || new Date().toISOString().slice(0,10),
                installments: [],

                init() {
                    // se veio old('installments') (erro de validação), usa-os
                    if (Array.isArray(oldInstallments) && oldInstallments.length) {
                        this.installments = oldInstallments.map((i, idx) => ({
                            installment_number: Number(i.installment_number ?? (idx+1)),
                            due_date: i.due_date ?? this.addMonthsISO(this.first, idx),
                            amount: Number(i.amount ?? 0),
                        }));
                    } else {
                        this.recompute();
                    }

                    this.$watch('total', () => this.recompute());
                    this.$watch('count', (val) => {
                        if (val < 1) this.count = 1;
                        this.recompute();
                    });
                    this.$watch('first', () => this.recomputeDates());
                },

                recompute() {
                    // recalcula datas + valores
                    this.recomputeDates();
                    this.recomputeAmounts();
                },

                recomputeDates() {
                    // garante o array no tamanho certo e datas mensais
                    const arr = [];
                    for (let i = 0; i < this.count; i++) {
                        const existing = this.installments[i];
                        arr.push({
                            installment_number: i + 1,
                            due_date: existing?.due_date ?? this.addMonthsISO(this.first, i),
                            amount: existing?.amount ?? 0,
                        });
                    }
                    this.installments = arr;
                },

                recomputeAmounts() {
                    if (this.count < 1) return;
                    const base = Math.floor((this.total / this.count) * 100) / 100; // 2 casas pra baixo
                    const arr = this.installments.map((inst) => ({...inst, amount: base}));

                    // Ajuste de centavos na última parcela
                    const distributed = base * this.count;
                    const diff = Math.round((this.total - distributed) * 100) / 100;
                    arr[this.count - 1].amount = Math.round((arr[this.count - 1].amount + diff) * 100) / 100;

                    this.installments = arr;
                },

                addMonthsISO(isoDate, monthsToAdd) {
                    if (!isoDate) return '';
                    const [y, m, d] = isoDate.split('-').map(Number);
                    const d0 = new Date(y, (m - 1) + monthsToAdd, 1);
                    // tenta manter o mesmo dia; se não existir (ex.: 31/02), usa último dia do mês
                    const targetMonth = d0.getMonth();
                    const lastDay = new Date(d0.getFullYear(), targetMonth + 1, 0).getDate();
                    const day = Math.min(d || 1, lastDay);
                    const final = new Date(d0.getFullYear(), targetMonth, day);
                    return final.toISOString().slice(0,10);
                },

                sumAmounts() {
                    return this.installments.reduce((s, i) => s + (Number(i.amount) || 0), 0);
                },

                formatBRL(v) {
                    const n = Number(v) || 0;
                    return n.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                },
            };
        }
    </script>
</x-ui.form-modal>
