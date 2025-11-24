<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <h2 class="fw-bold mb-1 fs-5">Cadastrar Novo Equipamento</h2>
        </div>
    </x-slot>

    <x-ui.flash-message />

    <div class="container-fluid">
        <div class="bg-white p-4 p-md-5 shadow-sm rounded">

            <form method="POST" action="{{ route('equipments.store') }}" enctype="multipart/form-data">
                @csrf

                <h5 class="mb-4 border-bottom pb-2 small text-uppercase text-muted">Dados Principais</h5>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Tipo *</label>
                    <div class="col-md-6">
                        <select class="form-select" name="equipment_type_id" required>
                            <option disabled selected>Selecione...</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" @selected(old('equipment_type_id') == $type->id)>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Nome *</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Status *</label>
                    <div class="col-md-6">
                        <select class="form-select" name="equipment_status_id" required>
                            <option disabled selected>Selecione...</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}" @selected(old('equipment_status_id') == $status->id)>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h5 class="mt-5 mb-4 border-bottom pb-2 small text-uppercase text-muted">Detalhes Técnicos</h5>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Marca</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="brand" value="{{ old('brand') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Modelo</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="model" value="{{ old('model') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Nº de Série</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="serial_number" value="{{ old('serial_number') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Patrimônio</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="asset_tag" value="{{ old('asset_tag') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Observações</label>
                    <div class="col-md-6">
                        <textarea class="form-control" rows="4" name="description">{{ old('description') }}</textarea>
                    </div>
                </div>

                <h5 class="mt-5 mb-4 border-bottom pb-2 small text-uppercase text-muted">Mídia</h5>

                <div class="row mb-4">
                    <label class="col-md-2 col-form-label">Fotos</label>
                    <div class="col-md-6">
                        <input type="file" class="form-control" name="photos[]" multiple accept="image/*">
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 offset-md-2">
                        <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
