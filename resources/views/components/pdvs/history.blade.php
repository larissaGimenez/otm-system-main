@props([
    'pdv'
])

@php
    // Os dados de exemplo continuam os mesmos
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

    // A CORREÇÃO ESTÁ AQUI
    // Convertemos cada item do array em um objeto (stdClass).
    // Isso garante a compatibilidade com o nosso `activity-log-panel` atualizado.
    $activities = collect($mockActivities)->map(fn($item) => (object)$item);

@endphp

{{-- A chamada ao painel de apresentação continua a mesma --}}
<x-ui.activity-log-panel title="Histórico do PDV" :records="$activities" />