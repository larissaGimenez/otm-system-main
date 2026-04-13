<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <h2 class="fw-bold mb-1 fs-5">Cadastrar Novo Equipamento</h2>
        </div>
    </x-slot>

    <style>
        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            display: none;
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .file-item {
            transition: all 0.2s;
        }

        .file-item:hover {
            background-color: #f8f9fa;
        }

        .form-control.is-invalid {
            background-image: none;
        }

        /* Limpa ícone padrão do bootstrap se preferir */
    </style>

    <div id="loadingOverlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
        <span class="mt-3 fw-bold text-dark">Processando informações, aguarde...</span>
    </div>

    <x-ui.flash-message />

    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0 mb-4">
                <div class="d-flex">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong class="d-block">Ops! Verifique os dados abaixo:</strong>
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white p-4 p-md-5 shadow-sm rounded">
            <form method="POST" action="{{ route('equipments.store') }}" enctype="multipart/form-data"
                id="equipmentForm">
                @csrf

                <h5 class="mb-4 border-bottom pb-2 small text-uppercase text-muted fw-bold">Dados Principais</h5>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label fw-semibold">Tipo *</label>
                    <div class="col-md-6">
                        <select class="form-select @error('equipment_type_id') is-invalid @enderror"
                            name="equipment_type_id" required>
                            <option value="" disabled selected>Selecione o tipo...</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" @selected(old('equipment_type_id') == $type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label fw-semibold">Nome *</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            value="{{ old('name') }}" placeholder="Ex: Notebook Dell Latitude" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label fw-semibold">Status *</label>
                    <div class="col-md-6">
                        <select class="form-select @error('equipment_status_id') is-invalid @enderror"
                            name="equipment_status_id" required>
                            <option value="" disabled selected>Selecione o status...</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" @selected(old('equipment_status_id') == $status->id)>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_status_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="mt-5 mb-4 border-bottom pb-2 small text-uppercase text-muted fw-bold">Detalhes Técnicos</h5>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label fw-semibold">Marca</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('brand') is-invalid @enderror" name="brand"
                            value="{{ old('brand') }}">
                        @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label fw-semibold">Modelo</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('model') is-invalid @enderror" name="model"
                            value="{{ old('model') }}">
                        @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label fw-semibold">Nº de Série</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('serial_number') is-invalid @enderror"
                            name="serial_number" value="{{ old('serial_number') }}">
                        @error('serial_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label fw-semibold">Patrimônio</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('asset_tag') is-invalid @enderror"
                            name="asset_tag" value="{{ old('asset_tag') }}">
                        @error('asset_tag') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label fw-semibold">Observações</label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('description') is-invalid @enderror" rows="4"
                            name="description">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="mt-5 mb-4 border-bottom pb-2 small text-uppercase text-muted fw-bold">Mídia</h5>

                <div class="row mb-4">
                    <label class="col-md-2 col-form-label fw-semibold">Fotos</label>
                    <div class="col-md-6">
                        <input type="file" class="form-control @error('photos*') is-invalid @enderror" name="photos[]"
                            id="photoInput" multiple accept="image/jpeg,image/png,image/webp">

                        <div class="form-text d-flex justify-content-between mt-2 small text-muted">
                            <span>Limite: <strong>Máx 5 fotos de 2MB cada</strong></span>
                            <span id="photoCounter">Selecionadas: <strong>0/5</strong></span>
                        </div>

                        <div id="fileList" class="mt-3"></div>

                        @error('photos*')
                            <div class="text-danger small mt-2 d-block fw-bold"><i class="bi bi-x-circle me-1"></i>
                                {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 offset-md-2 border-top pt-4">
                        <a href="{{ route('equipments.index') }}" class="btn btn-light border me-2 px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" id="submitBtn">
                            <i class="bi bi-check-lg me-1"></i> Salvar Equipamento
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const photoInput = document.getElementById('photoInput');
            const fileListContainer = document.getElementById('fileList');
            const submitBtn = document.getElementById('submitBtn');
            const equipmentForm = document.getElementById('equipmentForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const photoCounter = document.getElementById('photoCounter');

            let selectedFiles = [];

            photoInput.addEventListener('change', function () {
                const newFiles = Array.from(this.files);

                if ((selectedFiles.length + newFiles.length) > 5) {
                    alert("Você pode enviar no máximo 5 fotos.");
                    this.value = "";
                    return;
                }

                selectedFiles = [...selectedFiles, ...newFiles];
                renderFileList();
                this.value = "";
            });

            function renderFileList() {
                fileListContainer.innerHTML = '';
                const maxSize = 2 * 1024 * 1024; // 2MB
                let hasError = false;

                if (selectedFiles.length > 0) {
                    const listGroup = document.createElement('ul');
                    listGroup.className = 'list-group border rounded shadow-sm';

                    selectedFiles.forEach((file, index) => {
                        const isTooLarge = file.size > maxSize;
                        if (isTooLarge) hasError = true;

                        const li = document.createElement('li');
                        li.className = `list-group-item d-flex justify-content-between align-items-center file-item ${isTooLarge ? 'list-group-item-danger' : ''}`;

                        li.innerHTML = `
                                <div class="text-truncate" style="max-width: 75%;">
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 me-2 text-decoration-none" onclick="removeFile(${index})">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                    <small class="${isTooLarge ? 'fw-bold' : ''}">${file.name}</small>
                                </div>
                                <span class="badge ${isTooLarge ? 'bg-danger' : 'bg-success'} rounded-pill">
                                    ${(file.size / (1024 * 1024)).toFixed(2)} MB
                                </span>
                            `;
                        listGroup.appendChild(li);
                    });

                    fileListContainer.appendChild(listGroup);
                }

                photoCounter.innerHTML = `Selecionadas: <strong>${selectedFiles.length}/5</strong>`;

                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                photoInput.files = dataTransfer.files;

                // Regra: Não deixa salvar se tiver erro de tamanho ou zero arquivos (se fotos forem obrigatórias)
                submitBtn.disabled = hasError;
            }

            window.removeFile = function (index) {
                selectedFiles.splice(index, 1);
                renderFileList();
            };

            // Profissional: Evita double-submit e mostra feedback visual imediato
            equipmentForm.addEventListener('submit', function (e) {
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return;
                }

                loadingOverlay.style.display = 'flex';
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Salvando...`;
            });
        </script>
    @endpush
</x-app-layout>