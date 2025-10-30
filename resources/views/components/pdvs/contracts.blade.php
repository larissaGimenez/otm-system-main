{{-- Este é o componente principal da aba "Contratos" --}}
@props(['pdv'])

<div x-data="{ pdv: {{ $pdv->id }} }">
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createContractModal_{{ $pdv->id }}">
            <i class="bi bi-plus-circle me-1"></i> Adicionar Novo Contrato
        </button>
    </div>

    @forelse ($pdv->contracts as $contract)
        {{-- Para cada contrato, mostramos um painel de detalhes --}}
        @php
            // Prepara os dados para o 'details-panel'
            $detailsSections = [
                [
                    'title' => 'Termos do Contrato',
                    'rows' => [
                        ['label' => 'Data Assinatura', 'value' => $contract->signed_at->format('d/m/Y')],
                        ['label' => 'Nº Contrato', 'value' => $contract->id], // Ou um campo 'number' se vc tiver
                    ],
                ],
                [
                    'title' => 'Financeiro',
                    'rows' => [
                        ['label' => 'Mensalidade', 'value' => $contract->has_monthly_fee ? 'Sim' : 'Não'],
                        ['label' => 'Valor Mensal', 'value' => $contract->has_monthly_fee ? 'R$ ' . number_format($contract->monthly_fee_value, 2, ',', '.') : null],
                        ['label' => 'Dia Vencimento', 'value' => $contract->has_monthly_fee ? 'Dia ' . $contract->monthly_fee_due_day : null],
                        ['label' => 'Repasse (Comissão)', 'value' => $contract->has_commission ? 'Sim' : 'Não'],
                        ['label' => '% Repasse', 'value' => $contract->has_commission ? $contract->commission_percentage . '%' : null],
                    ],
                ],
                [
                    'title' => 'Dados de Pagamento',
                    'rows' => [
                        ['label' => 'Banco', 'value' => $contract->payment_bank_name],
                        ['label' => 'Agência', 'value' => $contract->payment_bank_agency],
                        ['label' => 'Conta', 'value' => $contract->payment_bank_account],
                        ['label' => 'Chave PIX', 'value' => $contract->payment_pix_key],
                    ],
                ],
            ];
        @endphp

        <div class="mb-2">
            {{-- Usamos o 'details-panel' que você me enviou --}}
            <x-ui.details-panel :sections="$detailsSections">
                {{-- Slot Padrão (para ações do contrato) --}}
                <div class="d-flex justify-content-end gap-2 mt-3 border-top pt-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editContractModal_{{ $contract->id }}">
                        <i class="bi bi-pencil me-1"></i> Editar Contrato
                    </button>
                    <form action="{{ route('contracts.destroy', $contract) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este contrato?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i> Excluir Contrato
                        </button>
                    </form>
                </div>
            </x-ui.details-panel>
        </div>

        {{-- Abaixo dos detalhes do contrato, usamos o 'crud-panel' para os faturamentos --}}
        <div class="mb-4 ps-md-4">
            @php
                $columns = [
                    ['label' => 'Período'],
                    ['label' => 'Venda Bruta (R$)'],
                    ['label' => 'Repasse (%)'],
                    ['label' => 'Valor Repasse (R$)'],
                ];
            @endphp
            <x-ui.crud-panel 
                title="Faturamentos Mensais"
                buttonText="Registrar Faturamento"
                :createModalTargetId="'createMonthlySaleModal_' . $contract->id"
                :records="$contract->monthlySales"
                :columns="$columns"
                emptyStateMessage="Nenhum faturamento registrado para este contrato."
            >
                @foreach ($contract->monthlySales as $sale)
                    <tr>
                        <td><strong>{{ str_pad($sale->month, 2, '0', STR_PAD_LEFT) }}/{{ $sale->year }}</strong></td>
                        <td>{{ number_format($sale->gross_sales_value, 2, ',', '.') }}</td>
                        <td>{{ $sale->contract->commission_percentage }}%</td>
                        {{-- Aqui usamos o campo virtual 'commission_value' que criamos no Model --}}
                        <td><strong>{{ number_format($sale->commission_value, 2, ',', '.') }}</strong></td>
                        <td class="text-end">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editMonthlySaleModal_{{ $sale->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('monthly-sales.destroy', $sale) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir este faturamento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-ui.crud-panel>
        </div>
    @empty
        <div class="text-center text-muted py-4">
            <i class="bi bi-file-earmark-text-fill fs-2 d-block mb-2"></i>
            <p>Nenhum contrato encontrado para este PDV.</p>
        </div>
    @endforelse
</div>