@props(['area'])

@php
    $teamCount = $area->teams->count();

    $tabs = [
        [ 'id' => 'details-tab', 'target' => 'details-tab-pane', 'label' => 'Detalhes', 'icon' => 'bi bi-info-circle-fill', 'active' => true ],
        [ 'id' => 'teams-tab', 'target' => 'teams-tab-pane', 'label' => "Equipes ({$teamCount})", 'icon' => 'bi bi-collection-fill', 'active' => false ],
        [ 'id' => 'logs-tab', 'target' => 'logs-tab-pane', 'label' => 'Logs', 'icon' => 'bi bi-clock-history', 'active' => false ],
    ];
@endphp

<x-ui.tab-navigation :tabs="$tabs" />