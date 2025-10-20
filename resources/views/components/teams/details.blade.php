@props(['team'])

@php
    $sections = [
        [
            'title' => 'Informações da Equipe',
            'rows' => [
                ['label' => 'Nome', 'value' => $team->name],
                ['label' => 'Descrição', 'value' => $team->description ?: 'Nenhuma descrição fornecida.'],
                ['label' => 'Status', 'value' => ucfirst($team->status)],
                ['label' => 'Criada em', 'value' => $team->created_at->format('d/m/Y \à\s H:i')],
            ],
        ],
    ];
@endphp

<x-ui.details-panel :sections="$sections" />