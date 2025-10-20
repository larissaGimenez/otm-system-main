@props(['area'])

@php
    // Usamos dados de exemplo até a implementação do log de atividades
    $mockActivities = [
        [ 'description' => 'A equipe <strong>Marketing Digital</strong> foi associada.', 'timestamp' => now()->subDay(), 'causer' => 'Admin', 'icon' => 'bi-plus-circle-fill', 'color' => 'text-primary' ],
        [ 'description' => 'Área criada no sistema.', 'timestamp' => now()->subWeek(), 'causer' => 'Admin', 'icon' => 'bi-check-circle-fill', 'color' => 'text-success' ],
    ];
    $activities = collect($mockActivities)->map(fn($i) => (object)$i);
@endphp

<x-ui.activity-log-panel title="Logs da Área" :records="$activities" />