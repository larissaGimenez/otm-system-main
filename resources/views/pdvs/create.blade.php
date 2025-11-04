<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Cadastrar Novo Ponto de Venda
        </h2>
    </x-slot>

    <x-ui.flash-message />

    {{-- O 'enctype' é essencial para o upload de arquivos --}}
    <form method="POST" action="{{ route('pdvs.store') }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong>Opa!</strong> Algo deu errado. Por favor, verifique os campos abaixo.
            </div>
        @endif
        
        <h5 class="mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados do PDV</h5>
        <div class="row">
            {{-- CAMPO NOME --}}
            <div class="col-md-7 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nome do PDV" required>
                    <label for="name">Nome do PDV</label>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- CAMPO TIPO --}}
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
                    <label for="type">Tipo</label>
                </div>
            </div>
            {{-- CAMPO STATUS --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="" disabled selected>Selecione um status...</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                {{ $status->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                    <label for="status">Status</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <textarea class="form-control" id="description" name="description" placeholder="Descrição do PDV" style="height: 100px">{{ old('description') }}</textarea>
                    <label for="description">Descrição (Opcional)</label>
                </div>
            </div>
        </div>

        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Endereço de Alocação</h5>
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="street" name="street" value="{{ old('street') }}" placeholder="Rua / Avenida">
                    <label for="street">Rua / Avenida</label>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="number" name="number" value="{{ old('number') }}" placeholder="Nº">
                    <label for="number">Número</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="complement" name="complement" value="{{ old('complement') }}" placeholder="Complemento">
                    <label for="complement">Complemento (Opcional)</label>
                </div>
            </div>
        </div>

        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Mídia</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="photos" class="form-label">Fotos</label>
                <input class="form-control @error('photos.*') is-invalid @enderror" type="file" id="photos" name="photos[]" multiple accept="image/*">
                @error('photos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="videos" class="form-label">Vídeos</label>
                <input class="form-control @error('videos.*') is-invalid @enderror" type="file" id="videos" name="videos[]" multiple accept="video/*">
                @error('videos.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('pdvs.index') }}" class="btn btn-outline-secondary me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                Salvar Ponto de Venda
            </button>
        </div>
    </form>
</x-app-layout>