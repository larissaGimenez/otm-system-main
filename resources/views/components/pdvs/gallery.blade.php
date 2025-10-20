@props([
    'pdv',
])

@php
    // Define a URL para o formulário de upload
    $storeUrl = route('pdvs.media.store', $pdv);

    // Formata o array de fotos
    $formattedPhotos = collect(is_array($pdv->photos) ? $pdv->photos : [])
        ->map(function ($photoPath, $index) use ($pdv) {
            return [
                'url' => 'storage/' . $photoPath,
                // CORREÇÃO: Alterado de 'photo' para 'photos' (plural) para corresponder à rota
                'destroyUrl' => route('pdvs.media.destroy', [$pdv, 'photos', $index]),
            ];
        })
        ->all();

    // Formata o array de vídeos
    $formattedVideos = collect(is_array($pdv->videos) ? $pdv->videos : [])
        ->map(function ($videoPath, $index) use ($pdv) {
            return [
                'url' => 'storage/' . $videoPath,
                // CORREÇÃO: Alterado de 'video' para 'videos' (plural) para corresponder à rota
                'destroyUrl' => route('pdvs.media.destroy', [$pdv, 'videos', $index]),
            ];
        })
        ->all();
@endphp

{{-- Chama o componente de apresentação com os dados corrigidos --}}
<x-ui.gallery-panel
    :photos="$formattedPhotos"
    :videos="$formattedVideos"
    :storeUrl="$storeUrl"
    entityName="PDV"
/>