@props([
    'equipment',
    'photoCount' => 0,
    // Você pode adicionar 'historyCount' aqui no futuro, se desejar
])

@php
    $tabs = [
        [
            'id' => 'details-tab',
            'target' => 'details-tab-pane', // O target deve ser o ID do wrapper
            'label' => 'Detalhes',
            'icon' => 'bi bi-info-circle-fill',
            'active' => true,
        ],
        [
            'id' => 'gallery-tab',
            'target' => 'gallery-tab-pane',
            'label' => "Galeria ({$photoCount})",
            'icon' => 'bi bi-images',
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

{{-- Este componente renderiza a lista de abas definida acima --}}
<x-ui.tab-navigation :tabs="$tabs" />