<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Cadastrar Novo Equipamento
        </h2>
    </x-slot>

    <x-ui.flash-message />

    <form method="POST" action="{{ route('equipments.store') }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong>Opa!</strong> Verifique os campos abaixo.
            </div>
        @endif
        
        <h5 class="mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados do Equipamento</h5>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nome do Equipamento" required>
                    <label for="name">Nome (Identificação)*</label>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="" disabled selected>Selecione um tipo...</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                {{ $type->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                    <label for="type">Tipo*</label>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand') }}" placeholder="Marca">
                    <label for="brand">Marca</label>
                    @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model') }}" placeholder="Modelo">
                    <label for="model">Modelo</label>
                    @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" placeholder="Nº de Série">
                    <label for="serial_number">Nº de Série (Opcional)</label>
                    @error('serial_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('asset_tag') is-invalid @enderror" id="asset_tag" name="asset_tag" value="{{ old('asset_tag') }}" placeholder="Etiqueta de Patrimônio">
                    <label for="asset_tag">Patrimônio (Opcional)</label>
                    @error('asset_tag') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="form-floating">
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Descrição (Opcional)" style="height: 100px">{{ old('description') }}</textarea>
                <label for="description">Descrição (Opcional)</label>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Mídia</h5>
        <div class="mb-3">
            <label for="photos" class="form-label">Fotos (Opcional)</label>
            <input class="form-control @error('photos.*') is-invalid @enderror" type="file" id="photos" name="photos[]" multiple accept="image/*">
            @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                Salvar Equipamento
            </button>
        </div>
    </form>
</x-app-layout>