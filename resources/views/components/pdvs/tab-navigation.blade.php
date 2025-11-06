@props([
    'pdv',
    'externalIdCount',
    // 'contractCount' => 0, // <-- REMOVIDO
])

@php
    $equipmentCount = $pdv->equipments->count();
    $photoCount = is_array($pdv->photos) ? count($pdv->photos) : 0;
    $videoCount = is_array($pdv->videos) ? count($pdv->videos) : 0;
    $mediaTotalCount = $photoCount + $videoCount;
    // $fee = $pdv->activationFee; // <-- REMOVIDO
    
    $tabs = [
        [
            'id' => 'details-tab',
            'target' => 'details-tab-pane',
            'label' => 'Detalhes',
            'icon' => 'bi bi-info-circle-fill',
            'active' => true,
        ],
        
        // ABA DE CONTRATOS REMOVIDA
        
        // ABA DE CUSTO DE IMPLANTAÇÃO REMOVIDA
        
        [
            'id' => 'equipments-tab',
            'target' => 'equipments-tab-pane',
            'label' => "Equipamentos ({$equipmentCount})",
            'icon' => 'bi bi-hdd-stack-fill',
            'active' => false,
        ],
        [
            'id' => 'gallery-tab',
            'target' => 'gallery-tab-pane',
            'label' => "Galeria ({$mediaTotalCount})",
            'icon' => 'bi bi-images',
            'active' => false,
        ],
        [
            'id' => 'history-tab',
            'target' => 'history-tab-pane',
            'label' => 'Histórico',
            'icon' => 'bi bi-clock-history',
            'active' => false,
        ],
        [
            'id' => 'extids-tab',
            'target' => 'extids-tab-pane',
            'label' => "IDs Externos ({$externalIdCount})",
            'icon' => 'bi bi-link-45deg',
            'active' => false,
        ],
    ];
@endphp

<x-ui.tab-navigation :tabs="$tabs" />