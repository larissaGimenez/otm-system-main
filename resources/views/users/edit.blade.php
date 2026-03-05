<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Editar Usuário: {{ $user->name }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <x-ui.flash-message />

    <div class="container-fluid">
        <div class="bg-white p-4 p-md-5 shadow-sm rounded">
            
            <form method="POST" action="{{ route('management.users.update', $user) }}" id="user-edit-form">
                @csrf
                @method('PUT')

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

                {{-- SEÇÃO: IDENTIFICAÇÃO PESSOAL --}}
                <h5 class="mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Identificação Pessoal
                </h5>

                <div class="row mb-3">
                    <label for="name" class="col-md-2 col-form-label">Nome Completo <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="cpf" class="col-md-2 col-form-label">CPF</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf" value="{{ old('cpf', $user->cpf) }}">
                        @error('cpf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="phone" class="col-md-2 col-form-label">Telefone / Celular <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- SEÇÃO: DADOS DE ACESSO --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Dados de Acesso
                </h5>

                <div class="row mb-3">
                    <label for="email" class="col-md-2 col-form-label">E-mail <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="role" class="col-md-2 col-form-label">Nível de Acesso <span class="text-danger">*</span></label>
                    <div class="col-md-6">
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}" @selected(old('role', $user->role) == $role->value)>
                                    {{ $role->getLabel() }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password" class="col-md-2 col-form-label">Nova Senha</label>
                    <div class="col-md-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                        <div class="form-text">Deixe em branco para não alterar.</div>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme a nova senha">
                    </div>
                </div>

                {{-- SEÇÃO: ENDEREÇO --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Endereço
                </h5>

                <div class="row mb-3">
                    <label for="postal_code" class="col-md-2 col-form-label">CEP</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
                        @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="street" class="col-md-2 col-form-label">Rua / Logradouro</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="street" name="street" value="{{ old('street', $user->street) }}">
                    </div>
                    <label for="number" class="col-md-1 col-form-label text-md-end">Nº</label>
                    <div class="col-md-1">
                        <input type="text" class="form-control" id="number" name="number" value="{{ old('number', $user->number) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="neighborhood" class="col-md-2 col-form-label">Bairro</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="neighborhood" name="neighborhood" value="{{ old('neighborhood', $user->neighborhood) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="city" class="col-md-2 col-form-label">Cidade / UF</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}">
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $user->state) }}" maxlength="2" placeholder="UF">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="complement" class="col-md-2 col-form-label">Complemento</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="complement" name="complement" value="{{ old('complement', $user->complement) }}">
                    </div>
                </div>

                {{-- BOTÕES --}}
                <div class="row mt-5">
                    <div class="col-md-6 offset-md-2">
                        <a href="{{ route('management.users.index') }}" class="btn btn-outline-secondary me-2">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Salvar Alterações
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        async function fetchAddress(event) {
            const cep = event.target.value.replace(/\D/g, '');
            if (cep.length !== 8) return;

            const fields = ['street', 'neighborhood', 'city', 'state'];
            fields.forEach(f => document.getElementById(f).value = '...');

            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                
                if (data.erro) {
                    alert('CEP não encontrado.');
                    fields.forEach(f => document.getElementById(f).value = '');
                    return;
                }
                
                document.getElementById('street').value = data.logradouro;
                document.getElementById('neighborhood').value = data.bairro;
                document.getElementById('city').value = data.localidade;
                document.getElementById('state').value = data.uf;
                document.getElementById('number').focus();
            } catch (error) {
                console.error('Erro ao buscar CEP:', error);
                fields.forEach(f => document.getElementById(f).value = '');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            let cpfMask, phoneMask, cepMask;
            
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) cpfMask = IMask(cpfInput, { mask: '000.000.000-00' });

            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneMask = IMask(phoneInput, {
                    mask: [ { mask: '(00) 0000-0000' }, { mask: '(00) 00000-0000' } ]
                });
            }

            const cepInput = document.getElementById('postal_code');
            if (cepInput) {
                cepMask = IMask(cepInput, { mask: '00000-000' });
                cepInput.addEventListener('blur', fetchAddress);
            }

            // Desmascara os dados antes de enviar para o Controller
            const form = document.getElementById('user-edit-form');
            if (form) {
                form.addEventListener('submit', function () {
                    if (cpfMask) cpfInput.value = cpfMask.unmaskedValue;
                    if (phoneMask) phoneInput.value = phoneMask.unmaskedValue;
                    if (cepMask) cepInput.value = cepMask.unmaskedValue;
                });
            }
        });
    </script>
    @endpush
</x-app-layout>