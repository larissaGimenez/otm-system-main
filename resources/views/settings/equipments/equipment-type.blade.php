<x-app-layout>
    <x-slot:header>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0 fs-3">Tipos de Equipamento</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTypeModal">
                <i class="bi bi-plus-lg"></i> Novo Tipo
            </button>
        </div>
    </x-slot:header>

    <x-ui.flash-message />

    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Nome</th>
                            <th>Uso</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($types as $type)
                            <tr>
                                <td class="fw-bold ps-4">{{ $type->name }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $type->equipments_count }} equipamentos
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">

                                        <button class="btn btn-outline-secondary btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editTypeModal"
                                                data-id="{{ $type->id }}"
                                                data-name="{{ $type->name }}"
                                                data-url="{{ route('settings.equipments.types.update', $type->id) }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <form action="{{ route('settings.equipments.types.destroy', $type->id) }}" 
                                              method="POST"
                                              onsubmit="return confirm('Tem certeza?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">Nenhum tipo cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL CRIAR --}}
    <x-ui.modal id="createTypeModal" title="Novo Tipo">
        <form action="{{ route('settings.equipments.types.store') }}" method="POST" id="createTypeForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" class="form-control" name="name" placeholder="Ex: Notebook" required>
            </div>
        </form>

        <x-slot:footer>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button form="createTypeForm" class="btn btn-primary">Salvar</button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- MODAL EDITAR --}}
    <x-ui.modal id="editTypeModal" title="Editar Tipo">
        <form method="POST" id="editTypeForm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" class="form-control" id="edit_name" name="name" required>
            </div>
        </form>

        <x-slot:footer>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button form="editTypeForm" class="btn btn-primary">Atualizar</button>
        </x-slot:footer>
    </x-ui.modal>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".btn-edit").forEach(btn => {
                btn.addEventListener("click", () => {
                    document.getElementById("editTypeForm").action = btn.dataset.url
                    document.getElementById("edit_name").value = btn.dataset.name
                })
            })
        })
    </script>
</x-app-layout>
