<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Cadastrar Nova Equipe
        </h2>
    </x-slot>

    {{-- Exibe erros gerais (do try...catch) --}}
    @if (session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('management.teams.store') }}" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-4">
            <h2 class="h5 font-weight-bold">Informações da Equipe</h2>
            <p class="text-muted small">Preencha os dados abaixo para criar uma nova equipe.</p>
        </div>

        {{-- DADOS DA EQUIPE --}}
        <h5 class="mt-5 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Dados da Equipe</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ old('name') }}" placeholder="Nome da Equipe" required maxlength="50">
                    <label for="name">Nome da Equipe</label>
                </div>
                <small class="form-text text-muted" id="name-char-counter">50 caracteres restantes</small>
            </div>
            <div class="col-md-6 mb-3">
            <div class="form-floating">
                <select class="form-select form-select-sm" id="status" name="status" required>
                    <option value="" disabled selected>Selecione um status...</option>
                    <option value="active" @selected(old('status') == 'active')>Ativa</option>
                    <option value="inactive" @selected(old('status') == 'inactive')>Inativa</option>
                </select>
                <label for="status">Status Inicial</label>
            </div>
        </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-floating">
                    <textarea class="form-control form-control-sm" id="description" name="description" placeholder="Descrição da Equipe" style="height: 100px" maxlength="500">{{ old('description') }}</textarea>
                    <label for="description">Descrição (Opcional)</label>
                </div>
                <small class="form-text text-muted" id="description-char-counter">500 caracteres restantes</small>
            </div>
        </div>

        {{-- MEMBROS DA EQUIPE --}}
        <h5 class="mt-4 mb-3 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">Membros da Equipe</h5>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="select-users" class="form-label">Adicionar Usuários</label>
                <select id="select-users" name="users[]" multiple placeholder="Selecione os usuários...">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('users.*')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- BOTÕES DE AÇÃO --}}
        <div class="mt-4 pt-3 border-top d-flex justify-content-end align-items-center">
            <a href="{{ route('management.teams.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                Salvar Equipe
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializa o seletor de múltiplos usuários com o Tom Select
            new TomSelect('#select-users', {
                plugins: ['remove_button'],
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });

            // Lógica para os contadores de caracteres
            function setupCharCounter(inputId, counterId, maxLength) {
                const input = document.getElementById(inputId);
                const counter = document.getElementById(counterId);

                if (input && counter) {
                    input.addEventListener('input', function() {
                        const remaining = maxLength - this.value.length;
                        counter.textContent = `${remaining} caracteres restantes`;
                    });
                }
            }

            setupCharCounter('name', 'name-char-counter', 50);
            setupCharCounter('description', 'description-char-counter', 500);
        });
    </script>
    @endpush
</x-app-layout>