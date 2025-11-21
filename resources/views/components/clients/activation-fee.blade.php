@props(['client'])

@php
    $fee = $client->activationFee;
    $paidValue = 0.0;
    $installmentsCount = 0;
    $isPaid = false;

    if ($fee) {
        $installments = $fee->installments ?? collect();
        $paidValue = $installments->sum('paid_value');
        $installmentsCount = $installments->count();
        
        // Verifica se todas estão pagas
        $isPaid = ($installmentsCount > 0 && $installments->every(fn($i) => $i->is_paid));

        $nextDue = $installments
            ->filter(fn($i) => !$i->is_paid && $i->due_date->isFuture())
            ->sortBy('due_date')
            ->first();
    }
@endphp

@if ($fee)
    @php
        $detailsSections = [
            [
                'title' => 'Resumo do Custo',
                'rows' => [
                    ['label' => 'Valor Total', 'value' => $fee->total_value !== null ? 'R$ ' . number_format((float)$fee->total_value, 2, ',', '.') : '—'],
                    ['label' => 'Total Pago',  'value' => 'R$ ' . number_format((float)$paidValue, 2, ',', '.')],
                    ['label' => 'Status',      'value' => $isPaid ? 'Quitado' : 'Pendente'],
                ],
            ],
            [
                'title' => 'Configuração',
                'rows' => [
                    ['label' => 'Total de Parcelas',   'value' => $installmentsCount],
                    ['label' => 'Próximo Vencimento', 'value' => isset($nextDue) && $nextDue?->due_date ? $nextDue->due_date->format('d/m/Y') : '—'],
                    ['label' => 'Observações',        'value' => $fee->notes ?: '—'],
                ],
            ],
        ];
    @endphp

    <x-ui.details-panel :sections="$detailsSections">
        <div class="d-flex justify-content-end gap-2 mt-3 border-top pt-3">
            {{-- Botão de Editar Custo REMOVIDO aqui --}}

            <form action="{{ route('clients.activation-fee.destroy', $client) }}"
                  method="POST"
                  onsubmit="return confirm('Tem certeza? Isso excluirá TODAS as parcelas associadas.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash me-1"></i> Excluir Custo
                </button>
            </form>
        </div>
    </x-ui.details-panel>

    <div class="mt-4">
        @php
            $columns = [
                ['label' => 'Nº'],
                ['label' => 'Vencimento'],
                ['label' => 'Valor (R$)'],
                ['label' => 'Pago em'], // Nome da coluna alterado
                ['label' => 'Status'],
            ];
        @endphp

        {{-- buttonText="" garante que não apareça botão de criar parcela --}}
        <x-ui.crud-panel
            title="Parcelas"
            buttonText="" 
            createModalTargetId="" 
            :records="$fee->installments"
            :columns="$columns"
            emptyStateMessage="Nenhuma parcela encontrada."
        >
            @foreach ($fee->installments as $installment)
                @php
                    $isPaid = $installment->is_paid; 
                    $isOverdue = $installment->is_overdue;
                @endphp

                <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                    <td><strong>{{ $installment->installment_number }}</strong></td>
                    <td>{{ $installment->due_date ? $installment->due_date->format('d/m/Y') : '—' }}</td>
                    
                    <td>{{ number_format((float)($installment->value ?? 0), 2, ',', '.') }}</td>
                    
                    {{-- Coluna Pago em (Apenas a data) --}}
                    <td>
                        {{ $installment->paid_at ? $installment->paid_at->format('d/m/Y') : '—' }}
                    </td>
                    
                    <td>
                        @if ($isPaid)
                            <span class="badge bg-success">Pago</span>
                        @elseif($isOverdue)
                            <span class="badge bg-danger">Vencido</span>
                        @else
                            <span class="badge bg-secondary">Pendente</span>
                        @endif
                    </td>

                    <td class="text-end">
                        @if (!$isPaid)
                            {{-- Botão Editar Vencimento (Só aparece se NÃO estiver pago) --}}
                            <button type="button" 
                                    class="btn btn-outline-primary btn-sm me-1"
                                    title="Alterar Vencimento"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editDueDateModal_{{ $installment->id }}">
                                <i class="bi bi-calendar-date"></i>
                            </button>

                            {{-- Botão Pagar --}}
                            <button type="button" class="btn btn-success btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#payInstallmentModal_{{ $installment->id }}">
                                <i class="bi bi-check-lg me-1"></i> Pagar
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </x-ui.crud-panel>
    </div>

@else
    <div class="text-center text-muted py-5">
        <i class="bi bi-cash-coin fs-2 d-block mb-3"></i>
        <h5 class="mb-3">Custo de Implantação</h5>
        <p>Nenhum custo de implantação foi configurado para este cliente.</p>

        <button type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#createActivationFeeModal_{{ $client->id }}">
            <i class="bi bi-plus-circle me-1"></i> Configurar Custo de Implantação
        </button>
    </div>
@endif

{{-- ======================================================= --}}
{{-- MODAIS (Renderizados no final da página)                --}}
{{-- ======================================================= --}}
@if ($fee)
    @foreach($fee->installments as $installment)
        
        {{-- MODAL 1: Editar Data de Vencimento --}}
        @if (!$installment->is_paid)
            <x-ui.form-modal 
                id="editDueDateModal_{{ $installment->id }}"
                title="Alterar Vencimento (Parcela #{{ $installment->installment_number }})"
                formAction="{{ route('fee-installments.update', $installment) }}" 
                formMethod="PUT"
            >
                <p class="small text-muted">
                    Altere a data de vencimento desta parcela.
                </p>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="form-floating">
                            <input 
                                type="date" 
                                class="form-control" 
                                id="due_date_{{ $installment->id }}" 
                                name="due_date" 
                                value="{{ old('due_date', $installment->due_date ? $installment->due_date->format('Y-m-d') : '') }}" 
                                required
                            >
                            <label for="due_date_{{ $installment->id }}">Nova Data de Vencimento</label>
                        </div>
                    </div>
                </div>
            </x-ui.form-modal>

            {{-- MODAL 2: Registrar Pagamento --}}
            <x-ui.form-modal 
                id="payInstallmentModal_{{ $installment->id }}"
                title="Confirmar Pagamento (Parcela #{{ $installment->installment_number }})"
                formAction="{{ route('fee-installments.pay', $installment) }}"
                formMethod="PATCH"
            >
                <div class="alert alert-success text-center">
                    <h5 class="alert-heading mb-1">R$ {{ number_format($installment->value, 2, ',', '.') }}</h5>
                    <span class="small">Valor Integral da Parcela</span>
                </div>
                
                <p class="text-center text-muted mb-4">
                    Confirme a data em que o pagamento foi realizado.
                </p>
                
                <div class="row justify-content-center">
                    <div class="col-md-8 mb-3">
                        <div class="form-floating">
                            <input 
                                type="date"
                                class="form-control text-center"
                                id="paid_at_{{ $installment->id }}"
                                name="paid_at"
                                value="{{ old('paid_at', now()->format('Y-m-d')) }}"
                                required
                            >
                            <label for="paid_at_{{ $installment->id }}">Data do Pagamento</label>
                        </div>
                    </div>
                </div>
            </x-ui.form-modal>
        @endif
    @endforeach
@endif