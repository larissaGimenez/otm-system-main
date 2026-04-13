@props([
    'photos' => [],
    'videos' => [],
    'storeUrl' => '#',
    'entityName' => 'item'
])

<div class="card border-0 shadow-sm">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h6 class="card-title text-muted small text-uppercase mb-0 fw-bold">Galeria de Mídia</h6>
                <small class="text-muted" style="font-size: 0.75rem;">Suporta JPG, PNG e WebP (Máx. 2MB)</small>
            </div>
            <button class="btn btn-primary btn-sm fw-bold shadow-sm px-3" type="button" onclick="document.getElementById('photoInput').click()">
                <i class="bi bi-camera-fill me-1"></i> Adicionar Foto(s)
            </button>
        </div>

        {{-- FORMULÁRIO DE UPLOAD --}}
        <form id="uploadForm" method="POST" action="{{ $storeUrl . '#gallery-tab-pane' }}" enctype="multipart/form-data">
            @csrf
            {{-- Input escondido --}}
            <input type="file" id="photoInput" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" class="d-none" onchange="handleFiles(this)">

            {{-- MODAL DE REVISÃO ESTILIZADO --}}
            <div class="modal fade" id="uploadVerifyModal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header border-bottom-0 pb-0">
                            <h5 class="modal-title fw-bold text-dark">Revisar Arquivos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetUpload()"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small mb-3">Verificamos os arquivos selecionados. Remova os itens em vermelho para habilitar o envio.</p>
                            
                            <div class="list-wrapper border rounded bg-light" style="max-height: 350px; overflow-y: auto;">
                                <ul class="list-group list-group-flush" id="modalFileList">
                                    {{-- Populado via JS --}}
                                </ul>
                            </div>

                            <div id="fileStats" class="mt-3 small text-end text-muted fw-bold">
                                {{-- Populado via JS --}}
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light border fw-bold px-4" data-bs-dismiss="modal" onclick="resetUpload()">Cancelar</button>
                            <button type="submit" id="confirmUploadBtn" class="btn btn-primary fw-bold px-4 shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-1"></i> Iniciar Upload
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <hr class="opacity-10">

        {{-- LISTAGEM DE FOTOS --}}
        <div class="row g-3 mt-2">
            @forelse ($photos as $photo)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card h-100 border shadow-xs gallery-item" style="transition: transform 0.2s;">
                        <div class="p-2 position-relative">
                            <img src="{{ asset($photo['url']) }}" class="img-fluid rounded shadow-sm" style="height: 120px; width: 100%; object-fit: cover;">
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0 text-center pb-2">
                            <form method="POST" action="{{ $photo['destroyUrl'] . '#gallery-tab-pane' }}" onsubmit="return confirm('Excluir esta foto permanentemente?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger btn-sm p-0 text-decoration-none fw-bold" style="font-size: 0.75rem;">
                                    <i class="bi bi-trash3"></i> Remover
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5 border rounded bg-light w-100 shadow-sm" style="border-style: dashed !important; border-width: 2px !important;">
                    <i class="bi bi-images fs-2 d-block mb-2 opacity-50"></i>
                    <p class="mb-0">Nenhuma foto enviada para este {{ $entityName }}.</p>
                </div>
            @endforelse
        </div>

        {{-- SEÇÃO DE VÍDEOS COMENTADA --}}
        {{-- 
        <h6 class="text-muted small text-uppercase mt-5 mb-2 fw-bold">Vídeos</h6>
        <div class="row g-3 mt-2">
             @forelse ($videos as $video)
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card h-100 border shadow-xs p-2">
                        <video controls class="w-100 rounded mb-2" style="max-height: 200px;">
                            <source src="{{ asset($video['url']) }}" type="video/mp4">
                        </video>
                        <form method="POST" action="{{ $video['destroyUrl'] . '#gallery-tab-pane' }}" onsubmit="return confirm('Remover vídeo?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">Remover</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4 w-100 border rounded bg-light">Nenhum vídeo enviado.</div>
            @endforelse
        </div> 
        --}}
    </div>
</div>

<style>
    .bg-light-danger { background-color: #fff5f5 !important; }
    .gallery-item:hover { transform: translateY(-3px); }
</style>

<script>
    let currentFiles = []; // Armazena os arquivos atuais para manipulação

    function handleFiles(input) {
        currentFiles = Array.from(input.files);
        renderModalList();
        
        const myModal = new bootstrap.Modal(document.getElementById('uploadVerifyModal'));
        myModal.show();
    }

    function renderModalList() {
        const listContainer = document.getElementById('modalFileList');
        const submitBtn = document.getElementById('confirmUploadBtn');
        const statsContainer = document.getElementById('fileStats');
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        
        let hasError = false;
        let totalSize = 0;

        listContainer.innerHTML = '';

        currentFiles.forEach((file, index) => {
            const isTooLarge = file.size > maxSize;
            const isAllowedType = allowedTypes.includes(file.type);
            const isInvalid = isTooLarge || !isAllowedType;
            
            if (isInvalid) hasError = true;
            totalSize += file.size;

            let errorLabel = isTooLarge ? 'Muito grande (>2MB)' : (!isAllowedType ? 'Formato inválido' : '');

            const li = document.createElement('li');
            li.className = `list-group-item d-flex justify-content-between align-items-center py-3 ${ isInvalid ? 'bg-light-danger' : '' }`;
            
            li.innerHTML = `
                <div class="text-truncate" style="max-width: 70%;">
                    <div class="d-flex align-items-center">
                        <i class="bi ${ isInvalid ? 'bi-x-circle-fill text-danger' : 'bi-check-circle-fill text-success' } me-2"></i>
                        <span class="small fw-bold text-dark text-truncate">${file.name}</span>
                    </div>
                    ${errorLabel ? `<span class="text-danger fw-bold d-block mt-1" style="font-size: 10px;">${errorLabel}</span>` : ''}
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill ${ isInvalid ? 'bg-danger' : 'bg-success' }">
                        ${(file.size / (1024 * 1024)).toFixed(2)} MB
                    </span>
                    <button type="button" class="btn btn-sm text-danger p-0" onclick="removeSingleFile(${index})">
                        <i class="bi bi-trash fs-5"></i>
                    </button>
                </div>
            `;
            listContainer.appendChild(li);
        });

        statsContainer.innerHTML = `Lote: ${currentFiles.length} | Total: ${(totalSize / (1024 * 1024)).toFixed(2)} MB`;

        // Habilita/Desabilita submit
        submitBtn.disabled = hasError || currentFiles.length === 0;
        submitBtn.className = `btn fw-bold px-4 shadow-sm ${hasError || currentFiles.length === 0 ? 'btn-secondary' : 'btn-primary'}`;
        
        // Sincroniza o input real com o array manipulado
        syncInput();
    }

    function removeSingleFile(index) {
        currentFiles.splice(index, 1);
        
        if (currentFiles.length === 0) {
            bootstrap.Modal.getInstance(document.getElementById('uploadVerifyModal')).hide();
            resetUpload();
        } else {
            renderModalList();
        }
    }

    function syncInput() {
        const input = document.getElementById('photoInput');
        const dt = new DataTransfer();
        currentFiles.forEach(file => dt.items.add(file));
        input.files = dt.files;
    }

    function resetUpload() {
        document.getElementById('photoInput').value = '';
        currentFiles = [];
    }

    document.getElementById('uploadForm').addEventListener('submit', function() {
        const btn = document.getElementById('confirmUploadBtn');
        const loader = document.getElementById('loadingOverlay');
        
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Subindo...`;
        
        if (loader) loader.style.display = 'flex';
    });
</script>