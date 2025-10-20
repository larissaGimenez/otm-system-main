<x-app-layout>
    @section('sidebar')
        @include('layouts._sidebar-admin')
    @endsection

    {{-- Adicionado enctype para permitir o upload de arquivos --}}
    <form method="POST" action="{{ route('admin.companies.update', $company) }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        @csrf
        @method('PUT') {{-- Essencial para a rota de update --}}

        <div class="mb-4">
            <h2 class="title-main">Editar Empresa: {{ $company->name }}</h2>
            <p class="text-muted">Altere os dados abaixo para atualizar a empresa.</p>
        </div>

        {{-- DADOS DA EMPRESA --}}
        <h5 class="mt-5 mb-3 border-bottom pb-2">Dados da Empresa</h5>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $company->name) }}" placeholder="Nome Fantasia" required>
                    <label for="name">Nome Fantasia</label>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="razao_social" name="razao_social" value="{{ old('razao_social', $company->razao_social) }}" placeholder="Razão Social" required>
                    <label for="razao_social">Razão Social</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="cnpj" name="cnpj" value="{{ old('cnpj', $company->cnpj) }}" placeholder="CNPJ" required>
                    <label for="cnpj">CNPJ</label>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" value="{{ old('inscricao_estadual', $company->inscricao_estadual) }}" placeholder="Inscrição Estadual">
                    <label for="inscricao_estadual">Inscrição Estadual (Opcional)</label>
                </div>
            </div>
        </div>

        {{-- CONTATO --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2">Contato</h5>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $company->email) }}" placeholder="E-mail de Contato">
                    <label for="email">E-mail de Contato</label>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="telefone" name="telefone" value="{{ old('telefone', $company->telefone) }}" placeholder="Telefone / Celular">
                    <label for="telefone">Telefone / Celular</label>
                </div>
            </div>
        </div>
        
        {{-- ENDEREÇO --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2">Endereço</h5>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep', $company->cep) }}" placeholder="CEP">
                    <label for="cep">CEP</label>
                </div>
            </div>
            <div class="col-md-7 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="logradouro" name="logradouro" value="{{ old('logradouro', $company->logradouro) }}" placeholder="Logradouro">
                    <label for="logradouro">Logradouro</label>
                </div>
            </div>
            <div class="col-md-2 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="numero" name="numero" value="{{ old('numero', $company->numero) }}" placeholder="Nº">
                    <label for="numero">Nº</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="bairro" name="bairro" value="{{ old('bairro', $company->bairro) }}" placeholder="Bairro">
                    <label for="bairro">Bairro</label>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="cidade" name="cidade" value="{{ old('cidade', $company->cidade) }}" placeholder="Cidade">
                    <label for="cidade">Cidade</label>
                </div>
            </div>
            <div class="col-md-2 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="estado" name="estado" value="{{ old('estado', $company->estado) }}" placeholder="UF">
                    <label for="estado">UF</label>
                </div>
            </div>
             <div class="col-md-2 mb-4">
                <div class="form-floating">
                    <input type="text" class="form-control" id="complemento" name="complemento" value="{{ old('complemento', $company->complemento) }}" placeholder="Complemento">
                    <label for="complemento">Complemento</label>
                </div>
            </div>
        </div>

        {{-- LOGO --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2">Identidade Visual</h5>
        <div class="row align-items-center">
            {{-- Coluna para a pré-visualização da logo atual --}}
            @if($company->logo_path)
                <div class="col-md-2 text-center">
                    <img src="{{ Storage::url($company->logo_path) }}" alt="Logo atual" class="img-thumbnail" style="max-height: 80px;">
                    <p class="text-muted small mt-1">Logo Atual</p>
                </div>
            @endif

            {{-- Coluna para o input de nova logo --}}
            <div class="col-md-10">
                <label for="logo_path" class="form-label">Alterar Logo da Empresa</label>
                <input class="form-control" type="file" id="logo_path" name="logo_path" accept="image/png, image/jpeg, image/svg+xml">
                <small class="text-muted">Envie um novo arquivo para substituir a logo atual.</small>
            </div>
        </div>

        {{-- BOTÕES DE AÇÃO --}}
        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                Salvar Alterações
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        // O mesmo script do create.blade.php funciona perfeitamente aqui.
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- INÍCIO: MÁSCARAS COM IMASK ---
            const cnpjInput = document.getElementById('cnpj');
            if (cnpjInput) {
                IMask(cnpjInput, { mask: '00.000.000/0000-00' });
            }

            const cepInput = document.getElementById('cep');
            if (cepInput) {
                IMask(cepInput, { mask: '00000-000' });
            }

            const telefoneInput = document.getElementById('telefone');
            if (telefoneInput) {
                IMask(telefoneInput, {
                    mask: [
                        { mask: '(00) 0000-0000' },
                        { mask: '(00) 00000-0000' }
                    ]
                });
            }
            // --- FIM: MÁSCARAS COM IMASK ---


            // --- INÍCIO: CONSULTA VIA CEP ---
            if (cepInput) {
                cepInput.addEventListener('blur', function() {
                    const cep = this.value.replace(/\D/g, '');

                    if (cep.length === 8) {
                        fetch(`https://viacep.com.br/ws/${cep}/json/`)
                            .then(response => response.json())
                            .then(data => {
                                if (!data.erro) {
                                    document.getElementById('logradouro').value = data.logradouro;
                                    document.getElementById('bairro').value = data.bairro;
                                    document.getElementById('cidade').value = data.localidade;
                                    document.getElementById('estado').value = data.uf;
                                    document.getElementById('numero').focus();
                                } else {
                                    alert('CEP não encontrado.');
                                }
                            })
                            .catch(error => console.error('Erro ao buscar CEP:', error));
                    }
                });
            }
            // --- FIM: CONSULTA VIA CEP ---
        });
    </script>
    @endpush

</x-app-layout>