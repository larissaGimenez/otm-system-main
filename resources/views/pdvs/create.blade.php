<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Cadastrar Novo Ponto de Venda
        </h2>
    </x-slot>

    {{-- Componente para exibir erros de validação do formulário --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- O 'enctype' é essencial para o upload de arquivos --}}
    <form method="POST" action="{{ route('pdvs.store') }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-4">
            <h2 class="h5 font-weight-bold">Informações do Ponto de Venda</h2>
            <p class="text-muted small">Preencha os dados abaixo para criar um novo PDV.</p>
        </div>

        {{-- DADOS DO PDV --}}
        <h5 class="mt-5 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados do PDV</h5>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ old('name') }}" placeholder="Nome do PDV" required>
                    <label for="name">Nome do PDV</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="type" name="type" value="{{ old('type') }}" placeholder="Tipo (ex: Quiosque)" required>
                    <label for="type">Tipo (ex: Quiosque, Loja)</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="status" name="status" value="{{ old('status') }}" placeholder="Status (ex: Ativo)" required>
                    <label for="status">Status (ex: Ativo, Em Manutenção)</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <textarea class="form-control form-control-sm" id="description" name="description" placeholder="Descrição do PDV" style="height: 100px">{{ old('description') }}</textarea>
                    <label for="description">Descrição (Opcional)</label>
                </div>
            </div>
        </div>

        {{-- ENDEREÇO --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Endereço de Alocação</h5>
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="street" name="street" value="{{ old('street') }}" placeholder="Rua / Avenida">
                    <label for="street">Rua / Avenida</label>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="number" name="number" value="{{ old('number') }}" placeholder="Nº">
                    <label for="number">Número</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="complement" name="complement" value="{{ old('complement') }}" placeholder="Complemento">
                    <label for="complement">Complemento (Opcional)</label>
                </div>
            </div>
        </div>

        {{-- MÍDIA --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Mídia</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="photos" class="form-label">Fotos</label>
                {{-- O 'multiple' permite o upload de vários arquivos --}}
                <input class="form-control form-control-sm" type="file" id="photos" name="photos[]" multiple accept="image/*">
                <small class="text-muted">Você pode selecionar múltiplas imagens.</small>
            </div>
            <div class="col-md-6 mb-3">
                <label for="videos" class="form-label">Vídeos</label>
                <input class="form-control form-control-sm" type="file" id="videos" name="videos[]" multiple accept="video/*">
                <small class="text-muted">Você pode selecionar múltiplos vídeos.</small>
            </div>
        </div>

        {{-- BOTÕES DE AÇÃO --}}
        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('pdvs.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                Salvar Ponto de Venda
            </button>
        </div>
    </form>
</x-app-layout>