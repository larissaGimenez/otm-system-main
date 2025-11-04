@props(['equipment'])

<x-ui.details-panel
    title="Resumo do Equipamento"
    :sections="[
        [
            'title' => 'Informações',
            'rows' => [
                ['label' => 'Nome', 'value' => $equipment->name],
                ['label' => 'Tipo', 'value' => $equipment->type->getLabel()], // MELHORIA
                ['label' => 'Status', 'value' => $equipment->status->getLabel()], // MELHORIA
                ['label' => 'Marca', 'value' => $equipment->brand],
                ['label' => 'Modelo', 'value' => $equipment->model],
                ['label' => 'Nº de Série', 'value' => $equipment->serial_number],
                ['label' => 'Patrimônio', 'value' => $equipment->asset_tag],
            ],
        ],
        [
            'title' => 'Descrição',
            'rows' => [
                ['label' => 'Notas', 'value' => $equipment->description],
            ],
        ],
    ]"
/>