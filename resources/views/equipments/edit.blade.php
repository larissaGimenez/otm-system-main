<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Editar Equipamento
        </h2>
    </x-slot>

    {{-- Mensagens de validação --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulário --}}
    <form method="POST" action="{{ route('equipments.update', $equipment) }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-4 d-flex justify-content-between align-items-start flex-wrap">
            <div>
                <h2 class="h5 font-weight-bold mb-1">Informações do Equipamento</h2>
                <p class="text-muted small mb-0">Atualize os dados abaixo e clique em salvar.</p>
            </div>
            <div class="small text-muted mt-2 mt-sm-0 text-end">
                <div class="mb-1">
                    <span class="badge {{ ($equipment->status ?? 'Disponível') === 'Disponível' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $equipment->status ?? 'Disponível' }}
                    </span>
                </div>
                <span class="me-2">Criado em: {{ $equipment->created_at?->format('d/m/Y H:i') }}</span>
                <span>Atualizado em: {{ $equipment->updated_at?->format('d/m/Y H:i') }}</span>
            </div>
        </div>

        {{-- DADOS GERAIS --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados Gerais</h5>

        <div class="row">
            {{-- NOME (máx. 50) --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating position-relative">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        id="name"
                        name="name"
                        value="{{ old('name', $equipment->name) }}"
                        placeholder="Nome do Equipamento"
                        maxlength="50"
                        required
                    >
                    <label for="name">Nome do Equipamento</label>
                    <small id="nameCounter" class="text-muted position-absolute end-0 bottom-0 me-2 mb-1 small">0 / 50</small>
                </div>
            </div>

            {{-- TIPO --}}
            <div class="col-md-3 mb-3">
                <div class="form-floating">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        id="type"
                        name="type"
                        value="{{ old('type', $equipment->type) }}"
                        placeholder="Tipo (ex: Impressora)"
                        required
                    >
                    <label for="type">Tipo</label>
                </div>
            </div>

            {{-- STATUS (somente visual) --}}
            <div class="col-md-3 mb-3">
                <div class="form-floating">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        id="status_view"
                        value="{{ $equipment->status ?? 'Disponível' }}"
                        placeholder="Status"
                        disabled
                    >
                    <label for="status_view">Status</label>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- MARCA --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        id="brand"
                        name="brand"
                        value="{{ old('brand', $equipment->brand) }}"
                        placeholder="Marca"
                    >
                    <label for="brand">Marca</label>
                </div>
            </div>

            {{-- MODELO --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        id="model"
                        name="model"
                        value="{{ old('model', $equipment->model) }}"
                        placeholder="Modelo"
                    >
                    <label for="model">Modelo</label>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Nº DE SÉRIE (único e opcional) --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        id="serial_number"
                        name="serial_number"
                        value="{{ old('serial_number', $equipment->serial_number) }}"
                        placeholder="Número de Série"
                    >
                    <label for="serial_number">Número de Série</label>
                </div>
                <small class="text-muted">Deixe em branco se não houver. Deve ser único no sistema.</small>
            </div>

            {{-- PATRIMÔNIO (único e opcional) --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        id="asset_tag"
                        name="asset_tag"
                        value="{{ old('asset_tag', $equipment->asset_tag) }}"
                        placeholder="Patrimônio"
                    >
                    <label for="asset_tag">Patrimônio</label>
                </div>
                <small class="text-muted">Deixe em branco se não houver. Deve ser único no sistema.</small>
            </div>
        </div>

        {{-- DESCRIÇÃO (máx. 500) --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Descrição</h5>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating position-relative">
                    <textarea
                        class="form-control form-control-sm"
                        id="description"
                        name="description"
                        placeholder="Descrição do Equipamento"
                        maxlength="500"
                        style="height: 120px"
                    >{{ old('description', $equipment->description) }}</textarea>
                    <label for="description">Descrição (Opcional)</label>
                    <small id="descCounter" class="text-muted position-absolute end-0 bottom-0 me-2 mb-1 small">0 / 500</small>
                </div>
            </div>
        </div>

        {{-- FOTOS --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Fotos do Equipamento</h5>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="photos" class="form-label">Adicionar novas fotos</label>
                <input class="form-control form-control-sm" type="file" id="photos" name="photos[]" multiple accept="image/*">
                <small class="text-muted">Você pode selecionar múltiplas imagens. Tamanho máximo por arquivo: 2 MB.</small>
            </div>
        </div>

        @if(!empty($equipment->photos) && is_array($equipment->photos))
            <div class="row g-3">
                <div class="col-12">
                    <div class="small text-muted mb-2">Fotos atuais</div>
                </div>
                @foreach ($equipment->photos as $photo)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="border rounded p-2 text-center">
                            <img src="{{ asset('storage/'.$photo) }}" alt="Foto do equipamento" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- BOTÕES --}}
        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                Salvar Alterações
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('name');
            const descInput = document.getElementById('description');
            const nameCounter = document.getElementById('nameCounter');
            const descCounter = document.getElementById('descCounter');

            const updateCounter = (input, counter, max) => {
                const len = input.value.length;
                counter.textContent = `${len} / ${max}`;
                counter.classList.toggle('text-danger', len > max);
            };

            if (nameInput && nameCounter) {
                updateCounter(nameInput, nameCounter, 50);
                nameInput.addEventListener('input', () => updateCounter(nameInput, nameCounter, 50));
            }

            if (descInput && descCounter) {
                updateCounter(descInput, descCounter, 500);
                descInput.addEventListener('input', () => updateCounter(descInput, descCounter, 500));
            }
        });
    </script>
    @endpush
</x-app-layout>
