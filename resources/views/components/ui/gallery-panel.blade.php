@props([
    'photos' => [],
    'videos' => [],
    'storeUrl' => '#',
    'entityName' => 'item'
])

@php
    $photoCount = count($photos);
    $videoCount = count($videos);
@endphp

{{-- Adicionamos x-data para inicializar o Alpine.js neste componente --}}
<div class="card border-0 shadow-sm" x-data>
    <div class="card-body">

        {{-- Bloco para exibir mensagens de sucesso e erro --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <h6 class="card-title text-muted small text-uppercase mb-2 mb-md-0">Galeria de Mídia</h6>

            {{-- NOVO BOTÃO DE UPLOAD COM MENU DROPDOWN --}}
            <div class="dropdown">
                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-plus-circle me-1"></i> Adicionar Mídia
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" @click.prevent="$refs.photoInput.click()"><i class="bi bi-image me-2"></i> Adicionar Foto(s)</a></li>
                    <li><a class="dropdown-item" href="#" @click.prevent="$refs.videoInput.click()"><i class="bi bi-film me-2"></i> Adicionar Vídeo(s)</a></li>
                </ul>
            </div>
        </div>

        {{-- FORMULÁRIO OCULTO que será acionado pelo Alpine.js --}}
        <form x-ref="uploadForm" method="POST" action="{{ $storeUrl . '#gallery-tab-pane' }}" enctype="multipart/form-data" class="d-none">
            @csrf
            {{-- O @change envia o formulário automaticamente ao selecionar um arquivo --}}
            <input type="file" x-ref="photoInput" name="photos[]" multiple accept="image/jpeg,image/png,image/gif,image/svg+xml" @change="$refs.uploadForm.submit()">
            <input type="file" x-ref="videoInput" name="videos[]" multiple accept="video/mp4,video/avi,video/mpeg" @change="$refs.uploadForm.submit()">
        </form>

        {{-- SEÇÃO DE FOTOS (permanece como no seu modelo antigo) --}}
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
                            <form method="POST" action="{{ $photo['destroyUrl'] . '#gallery-tab-pane' }}" onsubmit="return confirm('Tem certeza?')" class="mt-auto">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-trash me-1"></i> Remover</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- SEÇÃO DE VÍDEOS (permanece como no seu modelo antigo) --}}
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
                            <form method="POST" action="{{ $video['destroyUrl'] . '#gallery-tab-pane' }}" onsubmit="return confirm('Tem certeza?')" class="mt-auto">
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