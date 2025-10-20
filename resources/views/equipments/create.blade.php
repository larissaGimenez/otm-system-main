<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Cadastrar Novo Equipamento
        </h2>
    </x-slot>

    {{-- Exibir erros de validação --}}
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
    <form method="POST" action="{{ route('equipments.store') }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-4">
            <h2 class="h5 font-weight-bold">Informações do Equipamento</h2>
            <p class="text-muted small">Preencha os dados abaixo para cadastrar um novo equipamento.</p>
        </div>

        {{-- DADOS GERAIS --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados Gerais</h5>

        <div class="row">
            {{-- NOME --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating position-relative">
                    <input type="text"
                           class="form-control form-control-sm"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Nome do Equipamento"
                           maxlength="50"
                           required>
                    <label for="name">Nome do Equipamento</label>
                    <small id="nameCounter" class="text-muted position-absolute end-0 bottom-0 me-2 mb-1 small">0 / 50</small>
                </div>
            </div>

            {{-- TIPO --}}
            <div class="col-md-3 mb-3">
                <div class="form-floating">
                    <input type="text"
                           class="form-control form-control-sm"
                           id="type"
                           name="type"
                           value="{{ old('type') }}"
                           placeholder="Tipo (ex: Impressora)"
                           required>
                    <label for="type">Tipo</label>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- MARCA --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text"
                           class="form-control form-control-sm"
                           id="brand"
                           name="brand"
                           value="{{ old('brand') }}"
                           placeholder="Marca (ex: HP, Epson)">
                    <label for="brand">Marca</label>
                </div>
            </div>

            {{-- MODELO --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text"
                           class="form-control form-control-sm"
                           id="model"
                           name="model"
                           value="{{ old('model') }}"
                           placeholder="Modelo (ex: LaserJet 1020)">
                    <label for="model">Modelo</label>
                </div>
            </div>
        </div>

        {{-- DESCRIÇÃO --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Descrição</h5>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating position-relative">
                    <textarea class="form-control form-control-sm"
                              id="description"
                              name="description"
                              placeholder="Descrição do Equipamento"
                              maxlength="500"
                              style="height: 120px">{{ old('description') }}</textarea>
                    <label for="description">Descrição (Opcional)</label>
                    <small id="descCounter" class="text-muted position-absolute end-0 bottom-0 me-2 mb-1 small">0 / 500</small>
                </div>
            </div>
        </div>

        {{-- FOTOS --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Fotos do Equipamento</h5>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="photos" class="form-label">Selecionar Fotos</label>
                <input class="form-control form-control-sm" type="file" id="photos" name="photos[]" multiple accept="image/*">
                <small class="text-muted">Você pode selecionar múltiplas imagens. Tamanho máximo por arquivo: 2 MB.</small>
            </div>
        </div>

        {{-- BOTÕES --}}
        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                Salvar Equipamento
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
