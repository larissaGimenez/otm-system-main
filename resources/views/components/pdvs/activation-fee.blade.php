@props(['pdv'])

{{-- 
Este componente renderiza a aba "Custo de Implantação".
Ele é o orquestrador desta aba.
--}}

@php
    // O PdvController já carregou 'activationFee.installments' (eager load)
    $fee = $pdv->activationFee; 
@endphp

@if ($fee)
    {{-- 1. SE O CUSTO JÁ EXISTE, MOSTRAMOS OS DETALHES E AS PARCELAS --}}
    
    @php
        // Prepara os dados para o 'details-panel'
        $detailsSections = [
            [
                'title' => 'Resumo do Custo',
                'rows' => [
                    ['label' => 'Valor Total', 'value' => 'R$ ' . number_format($fee->total_value, 2, ',', '.')],
                    ['label' => 'Total Pago', 'value' => 'R$ ' . number_format($fee->paid_value, 2, ',', '.')],
                    ['label' => 'Status', 'value' => $fee->is_paid ? 'Quitado' : 'Pendente'],
                ],
            ],
            [
                'title' => 'Configuração',
                'rows' => [
                    ['label' => 'Forma Pagamento', 'value' => $fee->payment_method->getLabel()],
                    ['label' => 'Total de Parcelas', 'value' => $fee->installments_count],
                    ['label' => 'Venc. Monitoramento', 'value' => $fee->due_date ? $fee->due_date->format('d/m/Y') : null],
                    ['label' => 'Observações', 'value' => $fee->notes],
                ],
            ],
        ];
    @endphp

    {{-- Usamos o 'details-panel' para o resumo --}}
    <x-ui.details-panel :sections="$detailsSections">
        <div class="d-flex justify-content-end gap-2 mt-3 border-top pt-3">
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editActivationFeeModal_{{ $fee->id }}">
                <i class="bi bi-pencil me-1"></i> Editar Custo
            </button>
            <form action="{{ route('activation-fee.destroy', $fee) }}" method="POST" onsubmit="return confirm('Tem certeza? Isso excluirá TODAS as parcelas associadas.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-trash me-1"></i> Excluir Custo
                </button>
            </form>
        </div>
    </x-ui.details-panel>

    {{-- Usamos o 'crud-panel' para as parcelas (sem botão de criar) --}}
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
            buttonText="" {{-- Oculta o botão de criar --}}
            createModalTargetId=""
            :records="$fee->installments"
            :columns="$columns"
            emptyStateMessage="Nenhuma parcela encontrada."
        >
            @foreach ($fee->installments as $installment)
                <tr class="{{ $installment->is_overdue ? 'table-danger' : '' }}">
                    <td><strong>{{ $installment->installment_number }}</strong></td>
                    <td>{{ $installment->due_date->format('d/m/Y') }}</td>
                    <td>{{ number_format($installment->value, 2, ',', '.') }}</td>
                    <td>
                        @if ($installment->is_paid)
                            <span class="badge bg-success">Pago em {{ $installment->paid_at->format('d/m/Y') }}</span>
                        @elseif($installment->is_overdue)
                            <span class="badge bg-danger">Vencido</span>
                        @else
                            <span class="badge bg-secondary">Pendente</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($installment->is_paid)
                            {{-- Botão ESTORNAR --}}
                            <form action="{{ route('fee-installments.unpay', $installment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning btn-sm" title="Estornar Pagamento">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </form>
                        @else
                            {{-- Botão PAGAR --}}
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
    {{-- 2. SE O CUSTO AINDA NÃO EXISTE, MOSTRAMOS O BOTÃO DE CRIAR --}}
    <div class="text-center text-muted py-5">
        <i class="bi bi-cash-coin fs-2 d-block mb-3"></i>
        <h5 class="mb-3">Custo de Implantação</h5>
        <p>Nenhum custo de implantação foi configurado para este PDV.</p>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createActivationFeeModal_{{ $pdv->id }}">
            <i class="bi bi-plus-circle me-1"></i> Configurar Custo de Implantação
        </button>
    </div>
@endif