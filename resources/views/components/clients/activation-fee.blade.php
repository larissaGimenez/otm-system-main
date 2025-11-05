@props(['client'])

@php
    // Carrega a fee e as parcelas
    $fee = $client->activationFee;

    if ($fee) {
        $installments = $fee->installments ?? collect();

        // Total pago: soma apenas parcelas com paid_at
        $paidValue = $installments->reduce(function ($sum, $i) {
            return $sum + ((isset($i->paid_at) && $i->paid_at) ? (float) $i->amount : 0);
        }, 0.0);

        // Total de parcelas
        $installmentsCount = $installments->count();

        // Próximo vencimento: primeira parcela futura e não paga
        $nextDue = $installments
            ->filter(fn($i) => empty($i->paid_at) && $i->due_date && $i->due_date->isFuture())
            ->sortBy('due_date')
            ->first();

        // Status quitado: todas as parcelas pagas ou total pago >= total
        $isPaid = ($installmentsCount > 0 && $installments->every(fn($i) => !empty($i->paid_at)))
                  || ($fee->total_value && $paidValue >= (float) $fee->total_value);
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
                    ['label' => 'Total de Parcelas',  'value' => $installmentsCount],
                    ['label' => 'Próximo Vencimento', 'value' => isset($nextDue) && $nextDue?->due_date ? $nextDue->due_date->format('d/m/Y') : '—'],
                    ['label' => 'Observações',         'value' => $fee->notes ?: '—'],
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
                    $overdue = empty($installment->paid_at) && $installment->due_date && $installment->due_date->isPast();
                @endphp

                <tr class="{{ $overdue ? 'table-danger' : '' }}">
                    <td><strong>{{ $installment->installment_number }}</strong></td>
                    <td>{{ $installment->due_date ? $installment->due_date->format('d/m/Y') : '—' }}</td>
                    <td>{{ number_format((float)($installment->amount ?? 0), 2, ',', '.') }}</td>
                    <td>
                        @if (!empty($installment->paid_at))
                            <span class="badge bg-success">Pago em {{ $installment->paid_at->format('d/m/Y') }}</span>
                        @elseif($overdue)
                            <span class="badge bg-danger">Vencido</span>
                        @else
                            <span class="badge bg-secondary">Pendente</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if (!empty($installment->paid_at))
                            <form action="{{ route('fee-installments.unpay', $installment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-sm" title="Estornar Pagamento">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('fee-installments.pay', $installment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm" title="Marcar como Pago">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
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
