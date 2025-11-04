<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Cadastrar Novo Cliente
        </h2>
    </x-slot>

    <x-ui.flash-message />

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
             <strong>Opa!</strong> Algo deu errado. Por favor, verifique os campos abaixo.
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Dê um ID ao formulário para podermos selecioná-lo --}}
    <form method="POST" action="{{ route('clients.store') }}" id="client-form" class="bg-white p-4 rounded shadow-sm">
        @csrf

        {{-- DADOS DE IDENTIFICAÇÃO --}}
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
                    <input type="text" class="form-control @error('cnpj') is-invalid @enderror" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" placeholder="CNPJ" required>
                    <label for="cnpj">CNPJ</label>
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

        {{-- ENDEREÇO --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Endereço</h5>
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="CEP">
                    <label for="postal_code">CEP</label>
                    @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-7 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street') }}" placeholder="Logradouro">
                    <label for="street">Logradouro</label>
                    @error('street') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('number') is-invalid @enderror" id="number" name="number" value="{{ old('number') }}" placeholder="Nº">
                    <label for="number">Nº</label>
                    @error('number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('neighborhood') is-invalid @enderror" id="neighborhood" name="neighborhood" value="{{ old('neighborhood') }}" placeholder="Bairro">
                    <label for="neighborhood">Bairro</label>
                    @error('neighborhood') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4 mb-3">
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
             <div class="col-md-2 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control @error('complement') is-invalid @enderror" id="complement" name="complement" value="{{ old('complement') }}" placeholder="Complemento">
                    <label for="complement">Complemento</label>
                    @error('complement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        {{-- DADOS BANCÁRIOS --}}
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

        {{-- BOTÕES DE AÇÃO --}}
        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
            <button type="button" id="btn-fake-data" class="btn btn-outline-warning">
                <i class="bi bi-magic me-1"></i> Preencher (Teste)
            </button>
            
            <div>
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary me-2">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    Salvar Cliente
                </button>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        function fillFakeData() {
            const types = @json(collect($types)->pluck('value'));
            const banks = @json(collect($banks)->pluck('value'));
            const pixTypes = @json(collect($pixTypes)->pluck('value'));
            const randomType = types[Math.floor(Math.random() * types.length)];
            const randomBank = banks[Math.floor(Math.random() * banks.length)];
            const randomPixType = pixTypes[Math.floor(Math.random() * pixTypes.length)];
            
            const r = () => Math.floor(Math.random() * 10);
            const randNum = (len) => Math.random().toString().substring(2, len + 2);
            
            const fakeCnpj = `${r()}${r()}${r()}${r()}${r()}${r()}${r()}${r()}${r()}${r()}${r()}${r()}${r()}${r()}`;

            document.getElementById('name').value = 'Cliente Fictício (TESTE)';
            document.getElementById('cnpj').value = fakeCnpj;
            document.getElementById('type').value = randomType;
            
            document.getElementById('postal_code').value = '13010040';
            document.getElementById('postal_code').dispatchEvent(new Event('blur'));

            document.getElementById('number').value = randNum(3);
            document.getElementById('complement').value = 'Sala ' + randNum(2);

            document.getElementById('bank').value = randomBank;
            document.getElementById('agency').value = randNum(4);
            document.getElementById('account').value = randNum(6) + '-' + r();
            document.getElementById('pix_type').value = randomPixType;
            document.getElementById('pix_key').value = `(19) 9${randNum(4)}-${randNum(4)}`;
        }

        async function fetchAddress(event) {
            const cep = event.target.value.replace(/\D/g, '');
            if (cep.length !== 8) return;

            document.getElementById('street').value = 'Buscando...';
            document.getElementById('neighborhood').value = 'Buscando...';
            document.getElementById('city').value = 'Buscando...';
            document.getElementById('state').value = '...';

            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                if (data.erro) {
                    alert('CEP não encontrado.');
                    document.getElementById('street').value = '';
                    document.getElementById('neighborhood').value = '';
                    document.getElementById('city').value = '';
                    document.getElementById('state').value = '';
                    return;
                }
                document.getElementById('street').value = data.logradouro;
                document.getElementById('neighborhood').value = data.bairro;
                document.getElementById('city').value = data.localidade;
                document.getElementById('state').value = data.uf;
                document.getElementById('number').focus();
            } catch (error) {
                console.error('Erro ao buscar CEP:', error);
                alert('Falha ao consultar o CEP.');
                document.getElementById('street').value = '';
                document.getElementById('neighborhood').value = '';
                document.getElementById('city').value = '';
                document.getElementById('state').value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            
            // Variáveis para guardar as instâncias das máscaras
            let cepMask, cnpjMask;
            
            const cepInput = document.getElementById('postal_code');
            if (cepInput) {
                // Salva a instância da máscara
                cepMask = IMask(cepInput, { mask: '00000-000' });
                cepInput.addEventListener('blur', fetchAddress);
            }

            const cnpjInput = document.getElementById('cnpj');
            if (cnpjInput) {
                // Salva a instância da máscara
                cnpjMask = IMask(cnpjInput, { mask: '00.000.000/0000-00' });
            }

            const fakeButton = document.getElementById('btn-fake-data');
            if (fakeButton) {
                fakeButton.addEventListener('click', fillFakeData);
            }
            
            // --- A MÁGICA ACONTECE AQUI ---
            const form = document.getElementById('client-form');
            if (form) {
                form.addEventListener('submit', function (event) {
                    // No momento do submit, pegue o valor LIMPO da máscara
                    
                    if (cepMask) {
                        cepInput.value = cepMask.unmaskedValue;
                    }
                    if (cnpjMask) {
                        cnpjInput.value = cnpjMask.unmaskedValue;
                    }
                    
                    // O formulário agora envia os dados limpos
                });
            }
        });
    </script>
    @endpush
</x-app-layout>