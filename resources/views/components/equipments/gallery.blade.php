@props([
    'equipment', // Prop mudou de 'pdv' para 'equipment'
])

@php
    // URL para o formulário de upload
    $storeUrl = route('equipments.media.store', $equipment);

    // Formata o array de fotos
    $formattedPhotos = collect(is_array($equipment->photos) ? $equipment->photos : [])
        ->map(function ($photoPath, $index) use ($equipment) {
            return [
                'url' => 'storage/' . $photoPath,
                'destroyUrl' => route('equipments.media.destroy', [$equipment, 'photos', $index]),
            ];
        })
        ->all();

    // Formata o array de vídeos
    // (Assumindo que você vai querer adicionar vídeos, para espelhar o PDV)
    $formattedVideos = collect(is_array($equipment->videos) ? $equipment->videos : [])
        ->map(function ($videoPath, $index) use ($equipment) {
            return [
                'url' => 'storage/' . $videoPath,
                'destroyUrl' => route('equipments.media.destroy', [$equipment, 'videos', $index]),
            ];
        })
        ->all();
@endphp

{{-- Chama o componente de apresentação com os dados do equipamento --}}
<x-ui.gallery-panel
    :photos="$formattedPhotos"
    :videos="$formattedVideos"
    :storeUrl="$storeUrl"
    entityName="Equipamento" {{-- Nome da entidade atualizado --}}
/>