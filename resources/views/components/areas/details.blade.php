@props(['area'])

@php
    $sections = [
        [
            'title' => 'Informações da Área',
            'rows' => [
                ['label' => 'Nome', 'value' => $area->name],
                ['label' => 'Slug', 'value' => $area->slug],
                ['label' => 'Descrição', 'value' => $area->description ?: 'Nenhuma descrição fornecida.'],
                ['label' => 'Criada em', 'value' => $area->created_at->format('d/m/Y \à\s H:i')],
            ],
        ],
    ];
@endphp

<x-ui.details-panel :sections="$sections" />