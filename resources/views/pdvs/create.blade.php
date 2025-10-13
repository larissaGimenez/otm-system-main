<x-app-layout>
    {{-- Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Cadastrar Novo Usuário
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

    <form method="POST" action="{{ route('management.users.store') }}" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-4">
            <h2 class="h5 font-weight-bold">Informações do Novo Usuário</h2>
            <p class="text-muted small">Preencha os dados abaixo para criar um novo acesso ao sistema.</p>
        </div>

        {{-- DADOS PESSOAIS --}}
        <h5 class="mt-5 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados Pessoais</h5>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ old('name') }}" placeholder="Nome Completo" required>
                    <label for="name">Nome Completo</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="CPF">
                    <label for="cpf">CPF</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Telefone / Celular">
                    <label for="phone">Telefone / Celular</label>
                </div>
            </div>
        </div>

        {{-- DADOS DE ACESSO --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados de Acesso</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="email" class="form-control form-control-sm" id="email" name="email" value="{{ old('email') }}" placeholder="E-mail de Acesso" required>
                    <label for="email">E-mail de Acesso</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <select class="form-select form-select-sm" id="role" name="role" required>
                        <option value="" disabled selected>Selecione um cargo...</option>
                        <option value="staff" @selected(old('role') == 'staff')>Equipe Interna</option>
                        <option value="field" @selected(old('role') == 'field')>Equipe de Campo</option>
                        <option value="manager" @selected(old('role') == 'manager')>Gerente</option>
                        <option value="admin" @selected(old('role') == 'admin')>Administrador</option>
                    </select>
                    <label for="role">Cargo / Função</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Senha" required>
                    <label for="password">Senha</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" placeholder="Confirme a Senha" required>
                    <label for="password_confirmation">Confirme a Senha</label>
                </div>
            </div>
        </div>
        
        {{-- ENDEREÇO --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Endereço</h5>
        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="CEP">
                    <label for="postal_code">CEP</label>
                </div>
            </div>
            <div class="col-md-7 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="street" name="street" value="{{ old('street') }}" placeholder="Logradouro">
                    <label for="street">Logradouro</label>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="number" name="number" value="{{ old('number') }}" placeholder="Nº">
                    <label for="number">Nº</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="neighborhood" name="neighborhood" value="{{ old('neighborhood') }}" placeholder="Bairro">
                    <label for="neighborhood">Bairro</label>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="city" name="city" value="{{ old('city') }}" placeholder="Cidade">
                    <label for="city">Cidade</label>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="state" name="state" value="{{ old('state') }}" placeholder="UF">
                    <label for="state">UF</label>
                </div>
            </div>
             <div class="col-md-2 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="complement" name="complement" value="{{ old('complement') }}" placeholder="Complemento">
                    <label for="complement">Complemento</label>
                </div>
            </div>
        </div>

        {{-- BOTÕES DE AÇÃO --}}
        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('management.users.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                Salvar Usuário
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Aplica máscaras aos inputs
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) IMask(cpfInput, { mask: '000.000.000-00' });

            const cepInput = document.getElementById('postal_code');
            if (cepInput) IMask(cepInput, { mask: '00000-000' });

            const telefoneInput = document.getElementById('phone');
            if (telefoneInput) {
                IMask(telefoneInput, {
                    mask: [ { mask: '(00) 0000-0000' }, { mask: '(00) 00000-0000' } ]
                });
            }

            // Lógica para buscar endereço via CEP
            if (cepInput) {
                cepInput.addEventListener('blur', function() {
                    const cep = this.value.replace(/\D/g, '');
                    if (cep.length === 8) {
                        fetch(`https://viacep.com.br/ws/${cep}/json/`)
                            .then(response => response.json())
                            .then(data => {
                                if (!data.erro) {
                                    document.getElementById('street').value = data.logradouro;
                                    document.getElementById('neighborhood').value = data.bairro;
                                    document.getElementById('city').value = data.localidade;
                                    document.getElementById('state').value = data.uf;
                                    document.getElementById('number').focus();
                                } else {
                                    alert('CEP não encontrado.');
                                }
                            })
                            .catch(error => console.error('Erro ao buscar CEP:', error));
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>