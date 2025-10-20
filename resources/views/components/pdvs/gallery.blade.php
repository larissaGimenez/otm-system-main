@props([
    'pdv',
])

@php
    // 1. PREPARA OS DADOS ESPECÍFICOS DO PDV

    // Define a URL para o formulário de upload
    $storeUrl = route('pdvs.media.store', $pdv);

    // Formata o array de fotos para o formato que o componente de apresentação espera
    $formattedPhotos = collect(is_array($pdv->photos) ? $pdv->photos : [])
        ->map(function ($photoPath, $index) use ($pdv) {
            return [
                'url' => 'storage/' . $photoPath,
                'destroyUrl' => route('pdvs.media.destroy', [$pdv, 'photo', $index]),
            ];
        })
        ->all();

    // Formata o array de vídeos para o mesmo padrão
    $formattedVideos = collect(is_array($pdv->videos) ? $pdv->videos : [])
        ->map(function ($videoPath, $index) use ($pdv) {
            return [
                'url' => 'storage/' . $videoPath,
                'destroyUrl' => route('pdvs.media.destroy', [$pdv, 'video', $index]),
            ];
        })
        ->all();
@endphp

{{-- 
    2. CHAMA O COMPONENTE DE APRESENTAÇÃO
    Passa os dados já preparados e formatados para ele.
--}}
<x-ui.gallery-panel
    :photos="$formattedPhotos"
    :videos="$formattedVideos"
    :storeUrl="$storeUrl"
    entityName="PDV"
/>