<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Abrir Novo Chamado
        </h2>
    </x-slot>

    <x-ui.flash-message />

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            <form method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Opa!</strong> Verifique os campos abaixo.
                    </div>
                @endif

                <h5 class="mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Detalhes do Chamado</h5>

                <div class="mb-3">
                    <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição / Observações (Opcional)</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="attachment" class="form-label">Anexo (Opcional)</label>
                    <input 
                        type="file" 
                        class="form-control @error('attachment') is-invalid @enderror" 
                        id="attachment" 
                        name="attachment" 
                    >
                    @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Você pode anexar um único arquivo (PDF, JPG, PNG, ZIP, etc).</div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="area_id" class="form-label">Área <span class="text-danger">*</span></label>
                        <select class="form-select @error('area_id') is-invalid @enderror" id="area_id" name="area_id" required>
                            <option value="" disabled selected>Selecione a área responsável...</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('area_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="" disabled selected>Selecione o tipo...</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                    {{ $type->getLabel() }} 
                                </option>
                            @endforeach
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                {{-- Campos Prioridade e Status são definidos automaticamente no Controller --}}
                <input type="hidden" name="priority" value="{{ App\Enums\Request\RequestPriority::MEDIUM->value }}">
                <input type="hidden" name="status" value="{{ App\Enums\Request\RequestStatus::OPEN->value }}">

                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary me-2">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Abrir Chamado
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>