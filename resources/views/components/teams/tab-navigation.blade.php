@props(['team'])

@php
    // O controller já carregou os usuários, então a contagem é eficiente.
    $memberCount = $team->users->count();

    // Montamos o array de abas para o componente de UI
    $tabs = [
        [
            'id' => 'details-tab',
            'target' => 'details-tab-pane',
            'label' => 'Detalhes',
            'icon' => 'bi bi-info-circle-fill',
            'active' => true,
        ],
        [
            'id' => 'members-tab',
            'target' => 'members-tab-pane',
            'label' => "Membros ({$memberCount})",
            'icon' => 'bi bi-people-fill',
            'active' => false,
        ],
        [
            'id' => 'logs-tab',
            'target' => 'logs-tab-pane',
            'label' => 'Logs',
            'icon' => 'bi bi-clock-history',
            'active' => false,
        ],
    ];
@endphp

<x-ui.tab-navigation :tabs="$tabs" />