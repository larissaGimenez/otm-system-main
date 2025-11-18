<x-app-layout>
    <x-slot:header>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0 fs-3">Gerenciar Tipos de PDV</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTypeModal">
                <i class="bi bi-plus-lg"></i> Novo Tipo
            </button>
        </div>
    </x-slot:header>

    <x-ui.flash-message />

    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Nome</th>
                                <th class="py-3">Slug</th>
                                <th class="py-3">Uso</th>
                                <th class="text-end pe-4 py-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($types as $type)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $type->name }}</td>
                                    <td class="text-muted small">{{ $type->slug }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $type->pdvs_count }} PDVs</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            {{-- Botão Editar --}}
                                            <button type="button" 
                                                    class="btn btn-outline-secondary btn-edit"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTypeModal"
                                                    data-id="{{ $type->id }}"
                                                    data-name="{{ $type->name }}"
                                                    data-url="{{ route('settings.pdv.types.update', $type->id) }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            
                                            {{-- Botão Excluir --}}
                                            <form action="{{ route('settings.pdv.types.destroy', $type->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir o tipo {{ $type->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Nenhum tipo cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CRIAR --}}
    <x-ui.modal id="createTypeModal" title="Novo Tipo">
        <form action="{{ route('settings.pdv.types.store') }}" method="POST" id="createTypeForm">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nome do Tipo</label>
                <input type="text" class="form-control" name="name" placeholder="Ex: Quiosque" required>
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="createTypeForm" class="btn btn-primary">Salvar</button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- MODAL EDITAR --}}
    <x-ui.modal id="editTypeModal" title="Editar Tipo">
        <form method="POST" id="editTypeForm">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="edit_name" class="form-label">Nome do Tipo</label>
                <input type="text" class="form-control" name="name" id="edit_name" required>
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="editTypeForm" class="btn btn-primary">Atualizar</button>
        </x-slot:footer>
    </x-ui.modal>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.btn-edit');
            const editForm = document.getElementById('editTypeForm');
            const nameInput = document.getElementById('edit_name');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Pega a URL gerada pelo Blade no botão e coloca no action do form
                    const url = this.getAttribute('data-url');
                    const name = this.getAttribute('data-name');

                    editForm.setAttribute('action', url);
                    nameInput.value = name;
                });
            });
        });
    </script>
</x-app-layout>