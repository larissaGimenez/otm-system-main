<x-app-layout>
    {{-- 1. TÍTULO MOVIDO PARA O HEADER --}}
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Abrir Novo Chamado
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <x-ui.flash-message />
    
    <div class="container-fluid">
        <div class="bg-white p-4 p-md-5 shadow-sm rounded">
            
            <form 
                x-data="{ selectedType: '{{ old('type', '') }}' }" 
                method="POST" 
                action="{{ route('requests.store') }}" 
                enctype="multipart/form-data"
            >
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Opa!</strong> Verifique os campos abaixo.
                    </div>
                @endif

                <div class="row mb-3">
                    <label for="area_id" class="col-md-2 col-form-label">Departamento <span class="text-danger">*</span></label>
                    <div class="col-md-10">
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
                </div>

                <div class="row mb-3">
                    <label for="type" class="col-md-2 col-form-label">Tipo <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" 
                                x-model="selectedType" 
                                required>
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

                <div 
                    class="row mb-3" 
                    x-show="selectedType === 'manutencao_pdv'" 
                    style="display: none;"
                >
                    <label for="pdv_id" class="col-md-2 col-form-label">Cliente / PDV <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select class="form-select @error('pdv_id') is-invalid @enderror" id="pdv_id" name="pdv_id" 
                                :required="selectedType === 'manutencao_pdv'">
                            <option value="" selected>Nenhum PDV específico...</option>
                            @foreach ($pdvs as $pdv)
                                <option value="{{ $pdv->id }}" {{ old('pdv_id') == $pdv->id ? 'selected' : '' }}>
                                    {{ $pdv->client?->name ?? 'Sem Cliente' }} - {{ $pdv->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('pdv_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <label for="title" class="col-md-2 col-form-label">Assunto <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="col-md-2 col-form-label">Observações</label>
                    <div class="col-md-10">
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="8">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="attachment" class="col-md-2 col-form-label">Anexo</label>
                    <div class="col-md-10">
                        <input 
                            type="file" 
                            class="form-control @error('attachment') is-invalid @enderror" 
                            id="attachment" 
                            name="attachment" 
                        >
                        @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Você pode anexar um único arquivo (PDF, JPG, PNG, ZIP, etc).</div>
                    </div>
                </div>

                <input type="hidden" name="priority" value="{{ App\Enums\Request\RequestPriority::MEDIUM->value }}">
                <input type="hidden" name="status" value="{{ App\Enums\Request\RequestStatus::OPEN->value }}">

                <div class="row mt-4">
                    <div class="col-md-10 offset-md-2">
                        <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary me-2">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Abrir Chamado
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>