@props([
    'equipment', 
])

@php

    $storeUrl = route('equipments.media.store', $equipment);

    $formattedPhotos = collect(is_array($equipment->photos) ? $equipment->photos : [])
        ->map(function ($photoPath, $index) use ($equipment) {
            return [
                'url' => 'storage/' . $photoPath,
                'destroyUrl' => route('equipments.media.destroy', [$equipment, 'photos', $index]),
            ];
        })
        ->all();

    $formattedVideos = collect(is_array($equipment->videos) ? $equipment->videos : [])
        ->map(function ($videoPath, $index) use ($equipment) {
            return [
                'url' => 'storage/' . $videoPath,
                'destroyUrl' => route('equipments.media.destroy', [$equipment, 'videos', $index]),
            ];
        })
        ->all();
@endphp

<x-ui.gallery-panel
    :photos="$formattedPhotos"
    :videos="$formattedVideos"
    :storeUrl="$storeUrl"
    entityName="Equipamento" 
/>