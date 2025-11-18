<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Editar Ponto de Venda
        </h2>
    </x-slot>

    <x-ui.flash-message />

    <form method="POST" action="{{ route('pdvs.update', $pdv) }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT')

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong>Opa!</strong> Algo deu errado. Por favor, verifique os campos abaixo.
            </div>
        @endif

        <h5 class="mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados do PDV</h5>
        <div class="row">
            <div class="col-md-7 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $pdv->name) }}" placeholder="Nome do PDV" required>
                    <label for="name">Nome do PDV</label>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('pdv_type_id') is-invalid @enderror" id="pdv_type_id" name="pdv_type_id" required>
                        <option value="" disabled>Selecione um tipo...</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" {{ old('pdv_type_id', $pdv->pdv_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="pdv_type_id">Tipo</label>
                    @error('pdv_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('pdv_status_id') is-invalid @enderror" id="pdv_status_id" name="pdv_status_id" required>
                        <option value="" disabled>Selecione um status...</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ old('pdv_status_id', $pdv->pdv_status_id) == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="pdv_status_id">Status</label>
                    @error('pdv_status_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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