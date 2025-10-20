<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Detalhes do Equipamento
        </h2>
    </x-slot>

    {{-- Mensagens de sucesso/erro --}}
    @if(session('success'))<div class="alert alert-success mb-4">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger mb-4">{{ session('error') }}</div>@endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Cabeçalho de contexto --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-4">
                <div>
                    <h3 class="font-weight-bold mb-1">{{ $equipment->name }}</h3>
                    <p class="text-muted mb-2">
                        <span class="badge rounded-pill {{ ($equipment->status ?? 'Disponível') === 'Disponível' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $equipment->status ?? 'Disponível' }}
                        </span>
                        <span class="badge bg-light text-dark border ms-2">{{ $equipment->type }}</span>
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="bi bi-arrow-left me-1"></i> Voltar
                    </a>
                    <a href="{{ route('equipments.edit', $equipment) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-fill me-1"></i> Editar
                    </a>
                </div>
            </div>

            <hr>

            {{-- Navegação das abas --}}
            <ul class="nav nav-pills mb-3" id="equipment-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="pill" data-bs-target="#details-tab-pane" type="button" role="tab">
                        <i class="bi bi-info-circle-fill me-1"></i> Detalhes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="gallery-tab" data-bs-toggle="pill" data-bs-target="#gallery-tab-pane" type="button" role="tab">
                        <i class="bi bi-images me-1"></i> Galeria ({{ is_array($equipment->photos) ? count($equipment->photos) : 0 }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="pill" data-bs-target="#history-tab-pane" type="button" role="tab">
                        <i class="bi bi-clock-history me-1"></i> Histórico
                    </button>
                </li>
            </ul>

            {{-- Conteúdo das abas --}}
            <div class="tab-content" id="equipment-tabs-content">

                {{-- Aba: Detalhes --}}
                <x-details-card
                    title="Resumo do Equipamento"
                    :sections="[
                        [
                            'title' => 'Informações',
                            'rows' => [
                                ['label' => 'Nome',         'value' => $equipment->name],
                                ['label' => 'Tipo',         'value' => $equipment->type],
                                ['label' => 'Status',       'value' => $equipment->status],
                                ['label' => 'Marca',        'value' => $equipment->brand],
                                ['label' => 'Modelo',       'value' => $equipment->model],
                                ['label' => 'Nº de Série',  'value' => $equipment->serial_number],
                                ['label' => 'Patrimônio',   'value' => $equipment->asset_tag],
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


                {{-- Aba: Galeria --}}
                <div class="tab-pane fade" id="gallery-tab-pane" role="tabpanel" tabindex="0">
                    <div class="mt-4 card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                                <h6 class="card-title text-muted small text-uppercase mb-2 mb-md-0">Galeria de Fotos</h6>

                                {{-- Form para adicionar novas fotos --}}
                                <form method="POST" action="{{ route('equipments.photos.store', $equipment) }}" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <input class="form-control form-control-sm" type="file" id="photos" name="photos[]" multiple accept="image/*" required>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> Adicionar fotos
                                    </button>
                                </form>
                            </div>

                            @php
                                $photos = is_array($equipment->photos) ? $equipment->photos : [];
                            @endphp

                            @if(empty($photos))
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-image fs-2 d-block mb-2"></i>
                                    Nenhuma foto enviada para este equipamento.
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach ($photos as $i => $photo)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                                            <div class="border rounded p-2 text-center position-relative h-100 d-flex flex-column">
                                                <img src="{{ asset('storage/'.$photo) }}"
                                                     alt="Foto do equipamento"
                                                     class="img-fluid rounded mb-2"
                                                     style="max-height: 140px; object-fit: cover;">
                                                <form method="POST" action="{{ route('equipments.photos.destroy', [$equipment, $i]) }}" onsubmit="return confirm('Remover esta foto?')" class="mt-auto">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                        <i class="bi bi-trash me-1"></i> Remover
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Aba: Histórico (placeholder) --}}
                <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" tabindex="0">
                    <div class="mt-4 card border-0 shadow-sm">
                        <div class="card-body text-muted text-center py-5">
                            <i class="bi bi-clock-history fs-2 mb-2"></i>
                            <p class="mb-0">Nenhum histórico registrado para este equipamento.</p>
                        </div>
                    </div>
                </div>

            </div> {{-- fim tab-content --}}
        </div>
    </div>
</x-app-layout>
