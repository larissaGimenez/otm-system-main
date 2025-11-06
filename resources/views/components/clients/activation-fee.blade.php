@props(['client'])

@php
    $fee = $client->activationFee;
    $netBalance = 0.0;

    if ($fee) {
        $installments = $fee->installments ?? collect();
        $paidValue = $installments->sum('paid_value'); // Soma o que foi pago
        $installmentsCount = $installments->count();
        $isPaid = ($installmentsCount > 0 && $installments->every(fn($i) => $i->is_paid));

        if (!$isPaid) {
            $netBalance = round((float)$fee->total_value - $paidValue, 2);
        }

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
            <button type="button"
                    class="btn btn-outline-secondary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#editActivationFeeModal_{{ $client->id }}">
                <i class="bi bi-pencil me-1"></i> Editar Custo
            </button>

            @if ($netBalance > 0)
                <button type="button"
                        class="btn btn-info btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#renegotiateFeeModal_{{ $fee->id }}">
                    <i class="bi bi-arrow-repeat me-1"></i> 
                    Renegociar Saldo (R$ {{ number_format($netBalance, 2, ',', '.') }})
                </button>
            @endif

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
                ['label' => 'Valor Pago (R$)'],
                ['label' => 'Saldo (R$)'], // <-- Título da coluna
                ['label' => 'Status'],
            ];
        @endphp

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
                    $isPartial = $installment->paid_at !== null && !$isPaid;
                @endphp

                <tr class="{{ $isOverdue ? 'table-danger' : '' }}">
                    <td><strong>{{ $installment->installment_number }}</strong></td>
                    <td>{{ $installment->due_date ? $installment->due_date->format('d/m/Y') : '—' }}</td>
                    
                    <td>{{ number_format((float)($installment->value ?? 0), 2, ',', '.') }}</td>
                    
                    <td>{{ $installment->paid_value ? 'R$ ' . number_format((float)$installment->paid_value, 2, ',', '.') : '—' }}</td>

                    {{-- CORREÇÃO: Lógica do Saldo (+/-) --}}
                    <td>
                        @php $balance = (float)$installment->balance_value; @endphp
                        
                        @if ($balance > 0)
                            {{-- Pago a mais --}}
                            <span class="text-success fw-bold" title="Pago a mais">
                                + R$ {{ number_format($balance, 2, ',', '.') }}
                            </span>
                        @elseif ($balance < 0)
                            {{-- Devedor --}}
                            <span class="text-danger" title="Saldo devedor">
                                R$ {{ number_format($balance, 2, ',', '.') }}
                            </span>
                        @else
                            {{-- Quitado --}}
                            R$ 0,00
                        @endif
                    </td>
                    
                    <td>
                        @if ($isPaid)
                            <span class="badge bg-success">Pago</span>
                        @elseif ($isPartial)
                            <span class="badge bg-info">Pago Parcial</span>
                        @elseif($isOverdue)
                            <span class="badge bg-danger">Vencido</span>
                        @else
                            <span class="badge bg-secondary">Pendente</span>
                        @endif
                    </td>

                    <td class="text-end">
                        @if ($installment->paid_at !== null)
                            <form action="{{ route('fee-installments.unpay', $installment) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja estornar este pagamento? Isso irá zerar o valor pago.');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-sm" title="Estornar Pagamento">
                                    Estornar
                                </button>
                            </form>
                        @endif

                        @if (!$isPaid)
                            <button type="button" class="btn btn-success btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#payInstallmentModal_{{ $installment->id }}">
                                {{ $isPartial ? 'Ajustar Pag.' : 'Registrar Pag.' }}
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
{{-- MODAIS DE PAGAMENTO (Renderizados no final da página)   --}}
{{-- ======================================================= --}}
@if ($fee)
    @foreach($fee->installments as $installment)
        
        @if (!$installment->is_paid)
            <x-ui.form-modal 
                id="payInstallmentModal_{{ $installment->id }}"
                title="Registrar Pagamento (Parcela #{{ $installment->installment_number }})"
                formAction="{{ route('fee-installments.pay', $installment) }}"
                formMethod="PATCH"
            >
                <div class="alert alert-info small">
                    <b>Valor da Parcela:</b> R$ {{ number_format($installment->value, 2, ',', '.') }}<br>
                    @if($installment->paid_value > 0)
                        <b>Valor já pago:</b> R$ {{ number_format($installment->paid_value, 2, ',', '.') }}<br>
                        <b>Saldo Devedor:</b> R$ {{ number_format($installment->value - $installment->paid_value, 2, ',', '.') }}
                    @endif
                </div>
                
                <p class="small text-muted">
                    Informe o valor pago e a data.
                </p>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input 
                                type="number"
                                step="0.01"
                                class="form-control"
                                id="paid_value_{{ $installment->id }}"
                                name="paid_value"
                                {{-- Sugere o saldo devedor (valor - pago) --}}
                                value="{{ old('paid_value', number_format($installment->value - (float)$installment->paid_value, 2, '.', '')) }}"
                                placeholder="Valor Pago"
                                required
                            >
                            <label for="paid_value_{{ $installment->id }}">Valor Pago (R$)*</label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input 
                                type="date"
                                class="form-control"
                                id="paid_at_{{ $installment->id }}"
                                name="paid_at"
                                value="{{ old('paid_at', $installment->paid_at ? $installment->paid_at->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                required
                            >
                            <label for="paid_at_{{ $installment->id }}">Data do Pagamento*</label>
                        </div>
                    </div>
                </div>
            </x-ui.form-modal>
        @endif

        @if ($fee && $netBalance > 0)
            <x-ui.form-modal 
                id="renegotiateFeeModal_{{ $fee->id }}"
                title="Renegociar Saldo Devedor"
                formAction="{{ route('clients.activation-fee.installments.store', $client) }}"
                formMethod="POST"
                size="lg"
            >
                <div class="alert alert-info">
                    <b>Valor Total do Custo:</b> R$ {{ number_format($fee->total_value, 2, ',', '.') }}<br>
                    <b>Valor Total Pago:</b> R$ {{ number_format($paidValue, 2, ',', '.') }}<br>
                    <hr>
                    <b>Saldo Devedor a Renegociar: R$ {{ number_format($netBalance, 2, ',', '.') }}</b>
                </div>
                
                <p class="small text-muted">
                    Informe como você deseja parcelar este saldo devedor. 
                    <b>Atenção:</b> Todas as parcelas pendentes ou pagas parcialmente serão removidas e substituídas por esta nova configuração.
                </p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input 
                                type="number"
                                class="form-control"
                                id="renegotiate_installments_count"
                                name="installments_count"
                                value="{{ old('installments_count', 1) }}"
                                placeholder="1"
                                min="1"
                                required
                            >
                            <label for="renegotiate_installments_count">Nº de Parcelas*</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-floating">
                            <input 
                                type="date"
                                class="form-control"
                                id="renegotiate_first_due_date"
                                name="first_due_date"
                                value="{{ old('first_due_date', now()->format('Y-m-d')) }}"
                                required
                            >
                            <label for="renegotiate_first_due_date">Data Venc. 1ª Parcela*</label>
                        </div>
                    </div>
                </div>
            </x-ui.form-modal>
        @endif
    @endforeach
@endif