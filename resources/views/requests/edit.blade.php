<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Editar Chamado #{{ $request->id }}
        </h2>
    </x-slot>

    <x-ui.flash-message />

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            <form method="POST" action="{{ route('requests.update', $request) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Opa!</strong> Verifique os campos abaixo.
                    </div>
                @endif

                <h5 class="mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Detalhes do Chamado</h5>

                <div class="mb-3">
                    <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $request->title) }}" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição / Observações (Opcional)</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $request->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="attachment" class="form-label">Anexo (Opcional)</label>
                    
                    @if ($request->attachment_path)
                        <div class="mb-2">
                            <i class="bi bi-paperclip"></i>
                            <a href="{{ Storage::url($request->attachment_path) }}" target="_blank">
                                {{ $request->attachment_original_name ?? 'Ver Anexo' }}
                            </a>
                        </div>
                        <div class="form-text mb-2">Envie um novo arquivo abaixo para substituir o anexo atual.</div>
                    @endif

                    <input 
                        type="file" 
                        class="form-control @error('attachment') is-invalid @enderror" 
                        id="attachment" 
                        name="attachment" 
                    >
                    @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="area_id" class="form-label">Área <span class="text-danger">*</span></label>
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

                    <div class="col-md-6 mb-3">
                        <label for="pdv_id" class="form-label">PDV (Opcional)</label>
                        <select class="form-select @error('pdv_id') is-invalid @enderror" id="pdv_id" name="pdv_id">
                            <option value="" selected>Nenhum PDV específico...</option>
                            @foreach ($pdvs as $pdv)
                                {{-- Lógica de 'selected' atualizada para o edit --}}
                                <option value="{{ $pdv->id }}" {{ old('pdv_id', $request->pdv_id) == $pdv->id ? 'selected' : '' }}>
                                    {{ $pdv->name }} ({{ $pdv->client?->name ?? 'Sem Cliente' }})
                                </option>
                            @endforeach
                        </select>
                        @error('pdv_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
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

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="priority" class="form-label">Prioridade <span class="text-danger">*</span></label>
                        <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority->value }}" {{ old('priority', $request->priority->value) == $priority->value ? 'selected' : '' }}>
                                    {{ $priority->getLabel() }} 
                                </option>
                            @endforeach
                        </select>
                        @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
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

                <div class="mb-3">
                    <label for="due_at" class="form-label">Prazo (Opcional)</label>
                    <input type="date" class="form-control @error('due_at') is-invalid @enderror" id="due_at" name="due_at" value="{{ old('due_at', $request->due_at ? $request->due_at->format('Y-m-d') : '') }}">
                    @error('due_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('requests.show', $request) }}" class="btn btn-outline-secondary me-2">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>