<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Cadastrar Novo Cliente
        </h2>
    </x-slot>

    <x-ui.flash-message />

    {{-- Sem 'enctype' pois não há upload de arquivos --}}
    <form method="POST" action="{{ route('clients.store') }}" class="bg-white p-4 rounded shadow-sm">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong>Opa!</strong> Algo deu errado. Por favor, verifique os campos abaixo.
            </div>
        @endif
        
        <h5 class="mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Identificação</h5>
        <div class="row">
            <div class="col-md-7 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nome do Cliente" required>
                    <label for="name">Nome (Razão Social)</label>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-5 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('cnpj') is-invalid @enderror" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" placeholder="CNPJ (somente números)" maxlength="14" required>
                    <label for="cnpj">CNPJ (somente números)</label>
                    @error('cnpj') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="" disabled selected>Selecione um tipo...</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                {{ $type->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                    <label for="type">Tipo de Cliente</label>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Endereço</h5>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="CEP (somente números)" maxlength="8">
                    <label for="postal_code">CEP</label>
                    @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-8 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street') }}" placeholder="Rua / Avenida">
                    <label for="street">Rua / Avenida</label>
                    @error('street') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number') }}" placeholder="Nº">
                    <label for="number">Número</label>
                    @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-8 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('complement') is-invalid @enderror" id="complement" name="complement" value="{{ old('complement') }}" placeholder="Complemento">
                    <label for="complement">Complemento (Opcional)</label>
                    @error('complement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('neighborhood') is-invalid @enderror" id="neighborhood" name="neighborhood" value="{{ old('neighborhood') }}" placeholder="Bairro">
                    <label for="neighborhood">Bairro</label>
                    @error('neighborhood') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-5 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" placeholder="Cidade">
                    <label for="city">Cidade</label>
                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" placeholder="UF" maxlength="2">
                    <label for="state">UF</label>
                    @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados Bancários (Opcional)</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('bank') is-invalid @enderror" id="bank" name="bank">
                        <option value="" disabled selected>Selecione um banco...</option>
                        @foreach ($banks as $bank)
                            <option value="{{ $bank->value }}" {{ old('bank') == $bank->value ? 'selected' : '' }}>
                                {{ $bank->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                    <label for="bank">Banco</label>
                    @error('bank') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('agency') is-invalid @enderror" id="agency" name="agency" value="{{ old('agency') }}" placeholder="Agência">
                    <label for="agency">Agência</label>
                    @error('agency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('account') is-invalid @enderror" id="account" name="account" value="{{ old('account') }}" placeholder="Conta c/ dígito">
                    <label for="account">Conta</label>
                    @error('account') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <select class="form-select @error('pix_type') is-invalid @enderror" id="pix_type" name="pix_type">
                        <option value="" disabled selected>Selecione um tipo de chave PIX...</option>
                        @foreach ($pixTypes as $pixType)
                            <option value="{{ $pixType->value }}" {{ old('pix_type') == $pixType->value ? 'selected' : '' }}>
                                {{ $pixType->getLabel() }}
                            </option>
                        @endforeach
                    </select>
                    <label for="pix_type">Tipo de Chave PIX</label>
                    @error('pix_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('pix_key') is-invalid @enderror" id="pix_key" name="pix_key" value="{{ old('pix_key') }}" placeholder="Chave PIX">
                    <label for="pix_key">Chave PIX</label>
                    @error('pix_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                Salvar Cliente
            </button>
        </div>
    </form>
</x-app-layout>