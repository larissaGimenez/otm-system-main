@props(['client', 'pdvCount' => 0])

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
            'id' => 'pdvs-tab',
            'target' => 'pdvs-tab-pane',
            'label' => "PDVs ({$pdvCount})",
            'icon' => 'bi bi-shop',
            'active' => false,
        ],
        [
            'id' => 'history-tab',
            'target' => 'history-tab-pane',
            'label' => 'Histórico',
            'icon' => 'bi bi-clock-history',
            'active' => false,
        ],
    ];
@endphp

<x-ui.tab-navigation :tabs="$tabs" />