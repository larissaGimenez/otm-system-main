<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Editar Ponto de Venda
        </h2>
    </x-slot>

    <x-ui.flash-message />

    {{-- O 'enctype' é essencial para o upload de arquivos --}}
    <form method="POST" action="{{ route('pdvs.update', $pdv) }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        {{-- Exibe os erros de validação no topo do formulário --}}
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
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $pdv->name) }}" placeholder="Nome do PDV" required>
                    <label for="name">Nome do PDV</label>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            {{-- NOVO CAMPO CNPJ --}}
            <div class="col-md-5 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('cnpj') is-invalid @enderror" id="cnpj" name="cnpj" value="{{ old('cnpj', $pdv->cnpj) }}" placeholder="CNPJ (somente números)" maxlength="14">
                    <label for="cnpj">CNPJ (somente números)</label>
                    @error('cnpj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            {{-- CAMPO TIPO (CORRIGIDO PARA SELECT) --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="" disabled>Selecione um tipo...</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->value }}" {{ old('type', $pdv->type->value) == $type->value ? 'selected' : '' }}>
                                {{ $type->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                    <label for="type">Tipo</label>
                    @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            {{-- CAMPO STATUS (CORRIGIDO PARA SELECT) --}}
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="" disabled>Selecione um status...</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" {{ old('status', $pdv->status->value) == $status->value ? 'selected' : '' }}>
                                {{ $status->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                    <label for="status">Status</label>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description" name="description" placeholder="Descrição do PDV"
                              style="height: 100px">{{ old('description', $pdv->description) }}</textarea>
                    <label for="description">Descrição (Opcional)</label>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- ENDEREÇO --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Endereço de Alocação</h5>
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street', $pdv->street) }}" placeholder="Rua / Avenida">
                    <label for="street">Rua / Avenida</label>
                    @error('street')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number', $pdv->number) }}" placeholder="Nº">
                    <label for="number">Número</label>
                    @error('number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('complement') is-invalid @enderror" id="complement" name="complement" value="{{ old('complement', $pdv->complement) }}" placeholder="Complemento">
                    <label for="complement">Complemento (Opcional)</label>
                    @error('complement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        {{-- MÍDIA --}}
        @php
            $photoCount = is_array($pdv->photos) ? count($pdv->photos) : 0;
            $videoCount = is_array($pdv->videos) ? count($pdv->videos) : 0;
        @endphp
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Mídia</h5>
        <div class="alert alert-info py-2 small">
            Novos arquivos enviados serão <strong>adicionados</strong> aos existentes. Para remover, use a aba <em>Galeria</em> na página de detalhes do PDV.
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="photos" class="form-label">Fotos</label>
                <input class="form-control @error('photos.*') is-invalid @enderror"
                       type="file" id="photos" name="photos[]" multiple accept="image/*">
                <small class="text-muted d-block mt-1">
                    {{ $photoCount }} foto{{ $photoCount===1?'':'s' }} já enviada{{ $photoCount===1?'':'s' }}.
                </small>
                @error('photos.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="videos" class="form-label">Vídeos</label>
                <input class="form-control @error('videos.*') is-invalid @enderror"
                       type="file" id="videos" name="videos[]" multiple accept="video/*">
                <small class="text-muted d-block mt-1">
                    {{ $videoCount }} vídeo{{ $videoCount===1?'':'s' }} já enviado{{ $videoCount===1?'':'s' }}.
                </small>
                @error('videos.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- AÇÕES --}}
        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('pdvs.index') }}" class="btn btn-outline-secondary me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                Salvar Alterações
            </button>
        </div>
    </form>
</x-app-layout>