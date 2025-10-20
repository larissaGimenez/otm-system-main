@props([
    'photos' => [],         // Array de fotos. Ex: [['url' => 'path/to/img.jpg', 'destroyUrl' => 'route/to/destroy/1']]
    'videos' => [],         // Array de vídeos. Ex: [['url' => 'path/to/vid.mp4', 'destroyUrl' => 'route/to/destroy/2']]
    'storeUrl' => '#',      // A URL para o formulário de upload
    'entityName' => 'item'  // Nome genérico da entidade para mensagens (ex: "Foto do item")
])

@php
    $photoCount = count($photos);
    $videoCount = count($videos);
@endphp

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <h6 class="card-title text-muted small text-uppercase mb-2 mb-md-0">Galeria de Mídia</h6>

            {{-- Formulário de upload genérico --}}
            <form method="POST" action="{{ $storeUrl }}" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                @csrf
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <input class="form-control form-control-sm" type="file" name="photos[]" multiple accept="image/*" title="Selecionar fotos">
                    <input class="form-control form-control-sm" type="file" name="videos[]" multiple accept="video/*" title="Selecionar vídeos">
                </div>
                <button type="submit" class="btn btn-primary btn-sm flex-shrink-0">
                    <i class="bi bi-cloud-upload me-1"></i> Enviar
                </button>
            </form>
        </div>

        {{-- SEÇÃO DE FOTOS --}}
        <hr>
        <h6 class="text-muted small text-uppercase mt-4 mb-2">Fotos ({{ $photoCount }})</h6>
        @if($photoCount === 0)
            <div class="text-center text-muted py-4"><i class="bi bi-image fs-3 d-block mb-2"></i> Nenhuma foto enviada.</div>
        @else
            <div class="row g-3">
                @foreach ($photos as $photo)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="border rounded p-2 text-center h-100 d-flex flex-column">
                            <img src="{{ asset($photo['url']) }}" alt="Foto de {{ $entityName }}" class="img-fluid rounded mb-2" style="max-height: 140px; object-fit: cover;">
                            <form method="POST" action="{{ $photo['destroyUrl'] }}" onsubmit="return confirm('Tem certeza?')" class="mt-auto">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash me-1"></i> Remover</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- SEÇÃO DE VÍDEOS --}}
        <h6 class="text-muted small text-uppercase mt-4 mb-2">Vídeos ({{ $videoCount }})</h6>
        @if($videoCount === 0)
            <div class="text-center text-muted py-4"><i class="bi bi-film fs-3 d-block mb-2"></i> Nenhum vídeo enviado.</div>
        @else
            <div class="row g-3">
                @foreach ($videos as $video)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="border rounded p-2 h-100 d-flex flex-column">
                            <video controls class="w-100 rounded mb-2" style="max-height: 220px;">
                                <source src="{{ asset($video['url']) }}" type="video/mp4">
                            </video>
                            <form method="POST" action="{{ $video['destroyUrl'] }}" onsubmit="return confirm('Tem certeza?')" class="mt-auto">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash me-1"></i> Remover</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>