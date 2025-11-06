<x-app-layout>
    
    <x-ui.flash-message />

    <x-slot name="header">
        <div class="container-fluid">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Editar Chamado
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- 2. Wrapper principal (agora sem o h3) --}}
    <div class="container-fluid"> {{-- Adicionado container-fluid para evitar overflow --}}
        <div class="bg-white p-4 p-md-5 shadow-sm rounded">
            
            {{-- O h3 foi removido daqui --}}
            
            <form 
                x-data="{ selectedType: '{{ old('type', $request->type->value) }}' }" 
                method="POST" 
                action="{{ route('requests.update', $request) }}" 
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Opa!</strong> Verifique os campos abaixo.
                    </div>
                @endif

                {{-- Campos reordenados e reestilizados como no create.blade.php --}}
                <div class="row mb-3">
                    <label for="area_id" class="col-md-2 col-form-label">Departamento <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select class="form-select @error('area_id') is-invalid @enderror" id="area_id" name="area_id" required>
                            <option value="" disabled>Selecione a área responsável...</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" {{ old('area_id', $request->area_id) == $area->id ? 'selected' : '' }}>
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
                            <option value="" disabled>Selecione o tipo...</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->value }}" {{ old('type', $request->type->value) == $type->value ? 'selected' : '' }}>
                                    {{ $type->getLabel() }} 
                                </option>
                            @endforeach
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Campo condicional do PDV --}}
                <div 
                    class="row mb-3" 
                    x-show="selectedType === 'manutencao_pdv'" 
                    style="display: none;"
                >
                    <label for="pdv_id" class="col-md-2 col-form-label">Cliente / PDV <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select class="form-select @error('pdv_id') is-invalid @enderror" id="pdv_id" name="pdv_id" 
                                :required="selectedType === 'manutencao_pdv'">
                            <option value="">Nenhum PDV específico...</option>
                            @foreach ($pdvs as $pdv)
                                <option value="{{ $pdv->id }}" {{ old('pdv_id', $request->pdv_id) == $pdv->id ? 'selected' : '' }}>
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
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $request->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="col-md-2 col-form-label">Observações</label>
                    <div class="col-md-10">
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="8">{{ old('description', $request->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="attachment" class="col-md-2 col-form-label">Anexo</label>
                    <div class="col-md-10">
                        
                        @if ($request->attachment_path)
                            <div class="mb-2">
                                <i class="bi bi-paperclip"></i>
                                <a href="{{ Storage::url($request->attachment_path) }}" target="_blank" class="text-decoration-none fw-semibold">
                                    {{ $request->attachment_original_name ?? 'Ver Anexo Atual' }}
                                </a>
                            </div>
                            <div class="form-text mb-2">Envie um novo arquivo abaixo para substituir o anexo atual.</div>
                        @else
                             <div class="form-text">Você pode anexar um único arquivo (PDF, JPG, PNG, ZIP, etc).</div>
                        @endif

                        <input 
                            type="file" 
                            class="form-control @error('attachment') is-invalid @enderror" 
                            id="attachment" 
                            name="attachment" 
                        >
                        @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="row mb-3">
                    <label for="priority" class="col-md-2 col-form-label">Prioridade <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority->value }}" {{ old('priority', $request->priority->value) == $priority->value ? 'selected' : '' }}>
                                    {{ $priority->getLabel() }} 
                                </option>
                            @endforeach
                        </select>
                        @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="status" class="col-md-2 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-md-10">
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" {{ old('status', $request->status->value) == $status->value ? 'selected' : '' }}>
                                    {{ $status->getLabel() }} 
                                </option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="due_at" class="col-md-2 col-form-label">Prazo</label>
                    <div class="col-md-10">
                        <input type="date" class="form-control @error('due_at') is-invalid @enderror" id="due_at" name="due_at" value="{{ old('due_at', $request->due_at ? $request->due_at->format('Y-m-d') : '') }}">
                        @error('due_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-10 offset-md-2">
                        <a href="{{ route('requests.show', $request) }}" class="btn btn-outline-secondary me-2">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Salvar Alterações
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>