@props([
    'pdv'
])

@php
    // NOTA IMPORTANTE:
    // A melhor maneira de implementar histórico no Laravel é usando um pacote
    // como o 'spatie/laravel-activitylog'. O código abaixo simula os dados
    // que esse tipo de pacote geraria. Quando você implementar o backend,
    // a única coisa que precisará mudar é a origem da variável $activities.

    // Futuramente, seu código real seria algo assim:
    // $activities = \Spatie\Activitylog\Models\Activity::forSubject($pdv)->latest()->get();
    
    // Por enquanto, usamos dados de exemplo (mock):
    $mockActivities = [
        [
            'description' => 'O status foi alterado de <strong>Ativo</strong> para <strong>Inativo</strong>.',
            'timestamp' => now()->subHours(5),
            'causer' => 'Maria Silva',
            'icon' => 'bi-toggle-off',
            'color' => 'text-warning',
        ],
        [
            'description' => 'O equipamento <strong>TOTEM-003</strong> foi associado.',
            'timestamp' => now()->subDays(2),
            'causer' => 'João Pereira',
            'icon' => 'bi-hdd-stack-fill',
            'color' => 'text-primary',
        ],
        [
            'description' => 'PDV criado no sistema.',
            'timestamp' => now()->subDays(15),
            'causer' => 'João Pereira',
            'icon' => 'bi-plus-circle-fill',
            'color' => 'text-success',
        ]
    ];

    // Convertemos o array de exemplo em uma coleção para ser compatível
    $activities = collect($mockActivities)->map(function ($item) {
        $item['timestamp'] = is_string($item['timestamp']) ? \Carbon\Carbon::parse($item['timestamp']) : $item['timestamp'];
        return $item;
    });

@endphp

{{-- 
    Chama o painel de apresentação genérico, passando os dados
    (neste caso, os dados de exemplo que acabamos de criar).
--}}
<x-ui.activity-log-panel :records="$activities" />