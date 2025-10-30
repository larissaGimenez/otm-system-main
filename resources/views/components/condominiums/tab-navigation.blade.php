@props([
    'condominium',
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
            'id' => 'history-tab',
            'target' => 'history-tab-pane',
            'label' => 'Histórico',
            'icon' => 'bi bi-clock-history',
            'active' => false,
        ],
    ];
@endphp

<x-ui.tab-navigation :tabs="$tabs" />