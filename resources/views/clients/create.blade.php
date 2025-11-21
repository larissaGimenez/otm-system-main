<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Cadastrar Novo Cliente
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <x-ui.flash-message />

    <div class="container-fluid">
        <div class="bg-white p-4 p-md-5 shadow-sm rounded">
            
            <form method="POST" action="{{ route('clients.store') }}" id="client-form">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Opa!</strong> Verifique os campos abaixo.
                        <ul class="mb-0 mt-2 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- SEÇÃO: IDENTIFICAÇÃO --}}
                <h5 class="mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Identificação
                </h5>

                <div class="row mb-3">
                    <label for="name" class="col-md-2 col-form-label">Nome (Razão Social) <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="cnpj" class="col-md-2 col-form-label">CNPJ <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('cnpj') is-invalid @enderror" id="cnpj" name="cnpj" value="{{ old('cnpj') }}" required>
                        @error('cnpj') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="type" class="col-md-2 col-form-label">Tipo de Cliente <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="" disabled selected>Selecione um tipo...</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                    {{ $type->getLabel() }}
                                </option>
                            @endforeach
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- SEÇÃO: ENDEREÇO --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Endereço
                </h5>

                <div class="row mb-3">
                    <label for="postal_code" class="col-md-2 col-form-label">CEP</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                        @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Digite o CEP para buscar o endereço automaticamente.</div>
                    </div>
                </div>

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

                <div class="row mb-3">
                    <label for="neighborhood" class="col-md-2 col-form-label">Bairro</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('neighborhood') is-invalid @enderror" id="neighborhood" name="neighborhood" value="{{ old('neighborhood') }}">
                        @error('neighborhood') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="city" class="col-md-2 col-form-label">Cidade</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="state" class="col-md-2 col-form-label">UF</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" maxlength="2">
                        @error('state') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- SEÇÃO: DADOS BANCÁRIOS --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Dados Bancários (Opcional)
                </h5>

                <div class="row mb-3">
                    <label for="bank" class="col-md-2 col-form-label">Banco</label>
                    <div class="col-md-6">
                        <select name="bank" id="bank" class="form-select @error('bank') is-invalid @enderror">
                            <option value="" disabled selected>Selecione um banco...</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->value }}" {{ old('bank') == $bank->value ? 'selected' : '' }}>
                                    {{ $bank->getLabel() }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="agency" class="col-md-2 col-form-label">Agência</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('agency') is-invalid @enderror" id="agency" name="agency" value="{{ old('agency') }}">
                        @error('agency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="account" class="col-md-2 col-form-label">Conta</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('account') is-invalid @enderror" id="account" name="account" value="{{ old('account') }}" placeholder="Ex: 12345-6">
                        @error('account') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pix_type" class="col-md-2 col-form-label">Tipo Chave PIX</label>
                    <div class="col-md-6">
                        <select name="pix_type" id="pix_type" class="form-select @error('pix_type') is-invalid @enderror">
                            <option value="" disabled selected>Selecione o tipo...</option>
                            @foreach ($pixTypes as $pixType)
                                <option value="{{ $pixType->value }}" {{ old('pix_type') == $pixType->value ? 'selected' : '' }}>
                                    {{ $pixType->getLabel() }}
                                </option>
                            @endforeach
                        </select>
                        @error('pix_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="pix_key" class="col-md-2 col-form-label">Chave PIX</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('pix_key') is-invalid @enderror" id="pix_key" name="pix_key" value="{{ old('pix_key') }}">
                        @error('pix_key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- BOTÕES --}}
                <div class="row mt-5">
                    <div class="col-md-6 offset-md-2 d-flex justify-content-between">
                         {{-- Botão Fake Data (Mantido para testes) --}}
                        <button type="button" id="btn-fake-data" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-magic me-1"></i> Teste
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
                </div>

            </form>
        </div>
    </div>

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
            
            // Gerador simples de CNPJ (apenas numérico para passar na mask, validação real é outra história)
            // O ideal é usar um gerador válido se o backend validar dígito verificador.
            const fakeCnpj = '56.893.412/0001-56'; // Exemplo estático para não quebrar máscara complexa, ou use lógica de geração válida.

            document.getElementById('name').value = 'Cliente Fictício (TESTE)';
            // document.getElementById('cnpj').value = fakeCnpj; // O mask vai tratar isso
            
            // Disparar input event para máscaras pegarem se necessário, 
            // mas com IMask as vezes é melhor setar direto no value do elemento masked se tiver acesso, 
            // ou apenas o value e disparar events.
            
            document.getElementById('type').value = randomType;
            
            document.getElementById('postal_code').value = '13010040';
            document.getElementById('postal_code').dispatchEvent(new Event('blur')); // Dispara o ViaCEP

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