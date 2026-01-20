@props([
    'client',
    'pdvCount' => 0,
    'contractCount' => 0,
    'installmentsCount' => 0,
    'contactCount' => 0,
])

@php
    $tabs = [
        [
            'id' => 'details-tab',
            'target' => 'details-tab-pane',
            'label' => 'Detalhes',
            'icon' => 'bi bi-info-circle-fill',
            'active' => true,
        ],
        [
            'id' => 'contacts-tab',
            'target' => 'contacts-tab-pane',
            'label' => "Contatos ({$contactCount})",
            'icon' => 'bi bi-person-lines-fill',
            'active' => false,
        ],
        [
            'id' => 'pdvs-tab',
            'target' => 'pdvs-tab-pane',
            'label' => "PDVs ({$pdvCount})",
            'icon' => 'bi bi-shop',
            'active' => false,
        ],
        [
            'id' => 'contracts-tab',
            'target' => 'contracts-tab-pane',
            'label' => "Contratos ({$contractCount})",
            'icon' => 'bi bi-file-earmark-text',
            'active' => false,
        ],
        [
            'id' => 'activation-fee-tab',
            'target' => 'activation-fee-tab-pane',
            'label' => $installmentsCount > 0
                ? "Custo de Implantação ({$installmentsCount})"
                : 'Custo de Implantação',
            'icon' => 'bi bi-cash-coin',
            'active' => false,
        ],
        // [
        //     'id' => 'history-tab',
        //     'target' => 'history-tab-pane',
        //     'label' => 'Histórico',
        //     'icon' => 'bi bi-clock-history',
        //     'active' => false,
        // ],
    ];
@endphp

<x-ui.tab-navigation :tabs="$tabs" />
