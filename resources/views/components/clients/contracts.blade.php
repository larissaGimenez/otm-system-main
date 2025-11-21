@props(['client'])

@php
    $client->loadMissing(['contracts.monthlySales']);
    $contracts = $client->contracts ?? collect();
@endphp

<div>
    @if($contracts->isEmpty())
        <div class="d-flex justify-content-end mb-3">
            <button type="button"
                    class="btn btn-primary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#createContractModal_{{ $client->id }}">
                <i class="bi bi-plus-circle me-1"></i> Adicionar Novo Contrato
            </button>
        </div>
    @endif

    @forelse ($contracts as $contract)
        @php
            // Proteções para nulos + formatações
            $signedAt = $contract->signed_at
                ? $contract->signed_at->format('d/m/Y')
                : '—';

            $hasMonthly = (bool) ($contract->has_monthly_fee ?? false);
            $hasCommission = (bool) ($contract->has_commission ?? false);

            $detailsSections = [
                [
                    'title' => 'Termos do Contrato',
                    'rows' => [
                        ['label' => 'Data Assinatura', 'value' => $signedAt],
                        ['label' => 'Nº Contrato', 'value' => $contract->number ?? $contract->id],
                        [
                            'label' => 'Anexo', 
                            'value' => $contract->pdf_path 
                                ? '<a href="' . asset('storage/' . $contract->pdf_path) . '" target="_blank">Ver PDF <i class="bi bi-box-arrow-up-right"></i></a>' 
                                : '—',
                            'html' => true
                        ],
                    ],
                ],
                [
                    'title' => 'Financeiro',
                    'rows' => [
                        ['label' => 'Mensalidade', 'value' => $hasMonthly ? 'Sim' : 'Não'],
                        ['label' => 'Valor Mensal', 'value' => $hasMonthly ? ('R$ ' . number_format((float)($contract->monthly_fee_value ?? 0), 2, ',', '.')) : null],
                        ['label' => 'Dia Vencimento', 'value' => $hasMonthly ? ('Dia ' . ($contract->monthly_fee_due_day ?? '—')) : null],
                        ['label' => 'Repasse (Comissão)', 'value' => $hasCommission ? 'Sim' : 'Não'],
                        ['label' => '% Repasse', 'value' => $hasCommission ? (($contract->commission_percentage ?? 0) . '%') : null],
                    ],
                ],
            ];
        @endphp

        <div class="mb-2">
            <x-ui.details-panel :sections="$detailsSections">
                {{-- Ações do contrato --}}
                <div class="d-flex justify-content-end gap-2 mt-3 border-top pt-3">
                    {{-- Botão para modal de edição (ajuste o target/ID conforme seu modal de editar) --}}
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editContractModal_{{ $contract->id }}">
                        <i class="bi bi-pencil me-1"></i> Editar Contrato
                    </button>

                    {{-- Excluir contrato --}}
                    <form action="{{ route('contracts.destroy', $contract) }}"
                          method="POST"
                          onsubmit="return confirm('Tem certeza que deseja excluir este contrato?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash me-1"></i> Excluir Contrato
                        </button>
                    </form>
                </div>
            </x-ui.details-panel>
        </div>

        {{-- Faturamentos Mensais do contrato --}}
        @php
            $columns = [
                ['label' => 'Período'],
                ['label' => 'Venda Bruta (R$)'],
                ['label' => 'Repasse (%)'],
                ['label' => 'Valor Repasse (R$)'],
            ];
            $monthlySales = $contract->monthlySales ?? collect();
        @endphp

        <div class="mb-4 ps-md-4">
            <x-ui.crud-panel
                title="Faturamentos Mensais"
                buttonText="Registrar Faturamento"
                :createModalTargetId="'createMonthlySaleModal_' . $contract->id"
                :records="$monthlySales"
                :columns="$columns"
                emptyStateMessage="Nenhum faturamento registrado para este contrato."
            >
                @foreach ($monthlySales as $sale)
                    <tr>
                        <td><strong>{{ str_pad((string)($sale->month ?? 0), 2, '0', STR_PAD_LEFT) }}/{{ $sale->year ?? '—' }}</strong></td>
                        <td>{{ number_format((float)($sale->gross_sales_value ?? 0), 2, ',', '.') }}</td>
                        <td>{{ ($contract->commission_percentage ?? 0) }}%</td>
                        <td><strong>{{ number_format((float)($sale->commission_value ?? 0), 2, ',', '.') }}</strong></td>
                        <td class="text-end">
                            <button type="button"
                                    class="btn btn-outline-secondary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editMonthlySaleModal_{{ $sale->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <form action="{{ route('monthly-sales.destroy', $sale) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Excluir este faturamento?');">
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
            <p>Nenhum contrato encontrado para este cliente.</p>
        </div>
    @endforelse
</div>