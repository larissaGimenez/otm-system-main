<x-app-layout>
    {{-- 1. TÍTULO NO HEADER (Padrão do Modelo) --}}
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Cadastrar Novo Ponto de Venda
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <x-ui.flash-message />
    
    {{-- 2. CONTAINER PRINCIPAL --}}
    <div class="container-fluid">
        <div class="bg-white p-4 p-md-5 shadow-sm rounded">
            
            <form 
                method="POST" 
                action="{{ route('pdvs.store') }}" 
                enctype="multipart/form-data"
            >
                @csrf

                {{-- ALERTA DE ERROS --}}
                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Opa!</strong> Verifique os campos abaixo.
                    </div>
                @endif

                {{-- SEÇÃO: DADOS DO PDV --}}
                <h5 class="mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Dados Principais
                </h5>

                <div class="row mb-3">
                    <label for="name" class="col-md-2 col-form-label">Código do PDV <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="status" class="col-md-2 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="" disabled selected>Selecione um status...</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->getLabel() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="description" class="col-md-2 col-form-label">Observações</label>
                    <div class="col-md-6">
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Informações adicionais sobre este ponto de venda.</div>
                    </div>
                </div>

                {{-- SEÇÃO: ENDEREÇO --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Local de Instalação
                </h5>

                <div class="row mb-3">
                    <label for="street" class="col-md-2 col-form-label">Rua / Avenida</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street') }}">
                        @error('street') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="number" class="col-md-2 col-form-label">Número</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number') }}">
                        @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="complement" class="col-md-2 col-form-label">Complemento</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('complement') is-invalid @enderror" id="complement" name="complement" value="{{ old('complement') }}">
                        @error('complement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- SEÇÃO: MÍDIA --}}
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

                <div class="row mb-3">
                    <label for="videos" class="col-md-2 col-form-label">Vídeos</label>
                    <div class="col-md-6">
                        <input class="form-control @error('videos.*') is-invalid @enderror" type="file" id="videos" name="videos[]" multiple accept="video/*">
                        @error('videos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- BOTÕES DE AÇÃO (Alinhados com o grid) --}}
                <div class="row mt-5">
                    <div class="col-md-6 offset-md-2">
                        <a href="{{ route('pdvs.index') }}" class="btn btn-outline-secondary me-2">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Salvar Ponto de Venda
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>