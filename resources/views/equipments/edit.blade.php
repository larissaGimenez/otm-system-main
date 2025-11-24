<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold">Editar Equipamento</h2>
    </x-slot>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('equipments.update', $equipment) }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        <div class="d-flex justify-content-between mb-4">
            <div>
                <h5 class="fw-bold mb-1">Informações do Equipamento</h5>
            </div>
            <div class="small">
                @if($equipment->status)
                    <span style="color: {{ $equipment->status->color }}; font-weight:bold;">
                        • {{ $equipment->status->name }}
                    </span>
                @endif
            </div>
        </div>

        <h5 class="mt-3 mb-3 border-bottom pb-2 small text-uppercase text-muted">Dados Gerais</h5>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nome *</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $equipment->name) }}" required maxlength="50">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tipo *</label>
                <select class="form-select" name="equipment_type_id" required>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @selected(old('equipment_type_id', $equipment->equipment_type_id) == $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Status *</label>
                <select class="form-select" name="equipment_status_id" required>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}" @selected(old('equipment_status_id', $equipment->equipment_status_id) == $status->id)>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <h5 class="mt-4 mb-3 border-bottom pb-2 small text-uppercase text-muted">Detalhes Técnicos</h5>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Marca</label>
                <input type="text" class="form-control" name="brand" value="{{ old('brand', $equipment->brand) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Modelo</label>
                <input type="text" class="form-control" name="model" value="{{ old('model', $equipment->model) }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Número de Série</label>
                <input type="text" class="form-control" name="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Patrimônio</label>
                <input type="text" class="form-control" name="asset_tag" value="{{ old('asset_tag', $equipment->asset_tag) }}">
            </div>
        </div>

        <h5 class="mt-4 mb-3 border-bottom pb-2 small text-uppercase text-muted">Descrição</h5>

        <textarea class="form-control mb-4" rows="4" name="description">{{ old('description', $equipment->description) }}</textarea>

        <h5 class="mt-4 mb-3 border-bottom pb-2 small text-uppercase text-muted">Fotos</h5>

        <div class="mb-3">
            <label class="form-label">Adicionar novas fotos</label>
            <input type="file" class="form-control" name="photos[]" multiple accept="image/*">
        </div>

        @if ($equipment->photos)
            <div class="row g-3 mb-4">
                @foreach ($equipment->photos as $photo)
                    <div class="col-4 col-md-2">
                        <img src="{{ asset('storage/'.$photo) }}" class="img-fluid rounded border" style="height:100px;object-fit:cover;">
                    </div>
                @endforeach
            </div>
        @endif

        <div class="d-flex justify-content-end border-top pt-3">
            <a href="{{ route('equipments.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</x-app-layout>
