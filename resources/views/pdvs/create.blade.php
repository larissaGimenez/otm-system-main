<x-app-layout>
    {{-- 1. CABEÇALHO --}}
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

    {{-- 2. CONTAINER DO FORMULÁRIO (CARD BRANCO) --}}
    <div class="container-fluid">
        <div class="bg-white p-4 p-md-5 shadow-sm rounded">

            <form method="POST" action="{{ route('pdvs.store') }}" enctype="multipart/form-data">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Opa!</strong> Algo deu errado. Por favor, verifique os campos abaixo.
                    </div>
                @endif
                
                <h5 class="mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados do PDV</h5>

                
                {{-- CAMPO NOME E STATUS (COMBINADOS) --}}
                <div class="row mb-3">
                    {{-- NOME --}}
                    <label for="name" class="col-md-2 col-form-label">Nome do PDV <span class="text-danger">*</span></label>
                    <div class="col-md-4"> {{-- Ajustado de 6 para 4 --}}
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- STATUS (MOVIDO PARA CÁ) --}}
                    <label for="status" class="col-md-2 col-form-label">Status <span class="text-danger">*</span></label>
                    <div class="col-md-4">
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

                {{-- CAMPO TIPO (REMOVIDO) --}}
                
                {{-- CAMPO DESCRIÇÃO --}}
                <div class="row mb-3">
                    <label for="description" class="col-md-2 col-form-label">Descrição (Opcional)</label>
                    <div class="col-md-10">
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>


                <h5 class="mt-4 mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Endereço de Alocação</h5>

                {{-- CAMPO RUA --}}
                <div class="row mb-3">
                    <label for="street" class="col-md-2 col-form-label">Rua / Avenida</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street') }}">
                        @error('street') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- CAMPO NÚMERO --}}
                <div class="row mb-3">
                    <label for="number" class="col-md-2 col-form-label">Número</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number') }}">
                        @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                {{-- CAMPO COMPLEMENTO --}}
                <div class="row mb-3">
                    <label for="complement" class="col-md-2 col-form-label">Complemento (Opcional)</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control @error('complement') is-invalid @enderror" id="complement" name="complement" value="{{ old('complement') }}">
                        @error('complement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>


                <h5 class="mt-4 mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Mídia</h5>

                {{-- CAMPO FOTOS --}}
                <div class="row mb-3">
                    <label for="photos" class="col-md-2 col-form-label">Fotos</label>
                    <div class="col-md-10">
                        <input class="form-control @error('photos.*') is-invalid @enderror" type="file" id="photos" name="photos[]" multiple accept="image/*">
                        @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                {{-- CAMPO VÍDEOS --}}
                <div class="row mb-3">
                    <label for="videos" class="col-md-2 col-form-label">Vídeos</label>
                    <div class="col-md-10">
                        <input class="form-control @error('videos.*') is-invalid @enderror" type="file" id="videos" name="videos[]" multiple accept="video/*">
                        @error('videos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- 4. BOTÕES DE AÇÃO COM OFFSET --}}
                <div class="row mt-4">
                    <div class="col-md-10 offset-md-2">
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