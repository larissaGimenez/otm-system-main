<x-app-layout>
    <x-slot:header>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0 fs-3">Status de Equipamentos</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStatusModal">
                <i class="bi bi-plus-lg"></i> Novo Status
            </button>
        </div>
    </x-slot:header>

    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Nome</th>
                            <th>Cor</th>
                            <th>Uso</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statuses as $status)
                            <tr>
                                <td class="fw-bold ps-4">{{ $status->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $status->color }}">{{ $status->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $status->equipments_count }} equipamentos
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-secondary btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editStatusModal"
                                                data-id="{{ $status->id }}"
                                                data-name="{{ $status->name }}"
                                                data-color="{{ $status->color }}"
                                                data-url="{{ route('settings.equipments.statuses.update', $status->id) }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <form action="{{ route('settings.equipments.statuses.destroy', $status->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Tem certeza?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">Nenhum status cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL CRIAR --}}
    <x-ui.modal id="createStatusModal" title="Novo Status">
        <form action="{{ route('settings.equipments.statuses.store') }}" method="POST" id="createStatusForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Cor (Bootstrap)</label>
                <select name="color" class="form-select" required>
                    <option value="secondary" class="text-secondary">Cinza</option>
                    <option value="primary" class="text-primary">Azul</option>
                    <option value="success" class="text-success">Verde</option>
                    <option value="warning" class="text-warning">Amarelo</option>
                    <option value="danger" class="text-danger">Vermelho</option>
                    <option value="info" class="text-info">Azul Claro</option>
                    <option value="dark" class="text-dark">Preto</option>
                </select>
            </div>
        </form>

        <x-slot:footer>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button form="createStatusForm" class="btn btn-primary">Salvar</button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- MODAL EDITAR --}}
    <x-ui.modal id="editStatusModal" title="Editar Status">
        <form method="POST" id="editStatusForm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" class="form-control" id="edit_name" name="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Cor</label>
                <select name="color" id="edit_color" class="form-select" required>
                    <option value="secondary">Cinza</option>
                    <option value="primary">Azul</option>
                    <option value="success">Verde</option>
                    <option value="warning">Amarelo</option>
                    <option value="danger">Vermelho</option>
                    <option value="info">Azul Claro</option>
                    <option value="dark">Preto</option>
                </select>
            </div>
        </form>

        <x-slot:footer>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button form="editStatusForm" class="btn btn-primary">Atualizar</button>
        </x-slot:footer>
    </x-ui.modal>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".btn-edit").forEach(btn => {
                btn.addEventListener("click", () => {
                    document.getElementById("editStatusForm").action = btn.dataset.url
                    document.getElementById("edit_name").value = btn.dataset.name
                    document.getElementById("edit_color").value = btn.dataset.color
                })
            })
        })
    </script>
</x-app-layout>
