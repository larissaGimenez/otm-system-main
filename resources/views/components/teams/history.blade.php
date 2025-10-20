@props(['team'])

@php
    // No futuro, estes dados virão de um pacote como o spatie/laravel-activitylog.
    // Ex: $activities = Activity::forSubject($team)->latest()->get();
    
    // Por enquanto, usamos dados de exemplo (mock):
    $mockActivities = [
        [
            'description' => 'O membro <strong>Ana Carolina</strong> foi adicionado à equipe.',
            'timestamp' => now()->subHours(2),
            'causer' => 'Admin',
            'icon' => 'bi-person-plus-fill',
            'color' => 'text-primary',
        ],
        [
            'description' => 'O status foi alterado de <strong>active</strong> para <strong>inactive</strong>.',
            'timestamp' => now()->subDays(1),
            'causer' => 'Admin',
            'icon' => 'bi-toggle-off',
            'color' => 'text-warning',
        ],
        [
            'description' => 'Equipe criada no sistema.',
            'timestamp' => now()->subWeeks(2),
            'causer' => 'Admin',
            'icon' => 'bi-plus-circle-fill',
            'color' => 'text-success',
        ]
    ];

    $activities = collect($mockActivities)->map(fn($item) => (object)$item);

@endphp

<x-ui.activity-log-panel title="Logs da Equipe" :records="$activities" />