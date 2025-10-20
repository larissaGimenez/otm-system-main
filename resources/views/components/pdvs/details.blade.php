@props([
    'pdv',
])

@php
    // COMPONENTE DE CONTÊINER (DADOS)
    // A responsabilidade deste componente é preparar os dados específicos do PDV.

    $sections = [
        [
            'title' => 'Informações Gerais',
            'rows' => [
                ['label' => 'Nome', 'value' => $pdv->name],
                ['label' => 'Tipo', 'value' => $pdv->type],
                ['label' => 'Status', 'value' => $pdv->status],
                ['label' => 'Criado em', 'value' => $pdv->created_at->format('d/m/Y \à\s H:i')],
            ],
        ],
        [
            'title' => 'Endereço',
            'rows' => [
                [
                    'label' => 'Localidade',
                    'value' => trim(($pdv->street ?? '') . ($pdv->number ? ', ' . $pdv->number : '')),
                ],
                // Você pode adicionar mais campos do PDV aqui facilmente
                // ['label' => 'Bairro', 'value' => $pdv->neighborhood],
                // ['label' => 'Cidade/UF', 'value' => trim(($pdv->city ?? '') . ($pdv->state ? ' / ' . $pdv->state : ''))],
                // ['label' => 'CEP', 'value' => $pdv->zip_code],
            ],
        ],
    ];
@endphp

{{-- 
    Agora, ele chama o componente de apresentação genérico,
    passando os dados já formatados.
--}}
<x-ui.details-panel :sections="$sections" />