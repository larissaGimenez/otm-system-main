<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Cadastrar Novo Equipamento
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <x-ui.flash-message />
    
    <div class="container-fluid">
        <div class="bg-white p-4 p-md-5 shadow-sm rounded">
            
            <form method="POST" action="{{ route('equipments.store') }}" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Opa!</strong> Verifique os campos abaixo.
                    </div>
                @endif
                
                <h5 class="mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Dados Principais
                </h5>
                
                <div class="row mb-3">
                    <label for="equipment_type_id" class="col-md-2 col-form-label">Tipo <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <select class="form-select @error('equipment_type_id') is-invalid @enderror" id="equipment_type_id" name="equipment_type_id" required>
                            <option value="" disabled selected>Selecione um tipo...</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" {{ old('equipment_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="name" class="col-md-2 col-form-label">Nome (ID) <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ex: Notebook Dell 01" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="equipment_status_id" class="col-md-2 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <select class="form-select @error('equipment_status_id') is-invalid @enderror" id="equipment_status_id" name="equipment_status_id" required>
                            <option value="" disabled selected>Selecione o status inicial...</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" {{ old('equipment_status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_status_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="mt-5 mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Detalhes Técnicos
                </h5>

                <div class="row mb-3">
                    <label for="brand" class="col-md-2 col-form-label">Marca</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand" value="{{ old('brand') }}">
                        @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="model" class="col-md-2 col-form-label">Modelo</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model') }}">
                        @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="serial_number" class="col-md-2 col-form-label">Nº de Série</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('serial_number') is-invalid @enderror" id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
                        @error('serial_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="asset_tag" class="col-md-2 col-form-label">Patrimônio</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('asset_tag') is-invalid @enderror" id="asset_tag" name="asset_tag" value="{{ old('asset_tag') }}">
                        @error('asset_tag') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="col-md-2 col-form-label">Observações</label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <h5 class="mt-5 mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Mídia
                </h5>

                <div class="row mb-3">
                    <label for="photos" class="col-md-2 col-form-label">Fotos</label>
                    <div class="col-md-6">
                        <input class="form-control @error('photos.*') is-invalid @enderror" type="file" id="photos" name="photos[]" multiple accept="image/*">
                        @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Formatos aceitos: JPG, PNG. Múltiplos arquivos permitidos.</div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-6 offset-md-2">
                        <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary me-2">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Salvar Equipamento
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>