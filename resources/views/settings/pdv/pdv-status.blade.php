<x-app-layout>
    <x-slot:header>
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0 fs-3">Gerenciar Status de PDV</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStatusModal">
                <i class="bi bi-plus-lg"></i> Novo Status
            </button>
        </div>
    </x-slot:header>

    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Nome</th>
                                <th class="py-3">Cor (Badge)</th>
                                <th class="py-3">Uso</th>
                                <th class="text-end pe-4 py-3">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statuses as $status)
                                <tr>
                                    <td class="ps-4 fw-bold">{{ $status->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $status->color }}">{{ $status->name }}</span>
                                        <small class="text-muted ms-2">({{ $status->color }})</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $status->pdvs_count }} PDVs</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" 
                                                    class="btn btn-outline-secondary btn-edit"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editStatusModal"
                                                    data-id="{{ $status->id }}"
                                                    data-name="{{ $status->name }}"
                                                    data-color="{{ $status->color }}"
                                                    data-url="{{ route('settings.pdv.statuses.update', $status->id) }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            
                                            <form action="{{ route('settings.pdv.statuses.destroy', $status->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Tem certeza? Isso não pode ser desfeito.');">
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
                                    <td colspan="5" class="text-center py-4 text-muted">Nenhum status cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CRIAR --}}
    <x-ui.modal id="createStatusModal" title="Novo Status">
        <form action="{{ route('settings.pdv.statuses.store') }}" method="POST" id="createStatusForm">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nome do Status</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Cor (Bootstrap)</label>
                <select name="color" class="form-select" required>
                    <option value="secondary" class="text-secondary">Secondary (Cinza)</option>
                    <option value="primary" class="text-primary">Primary (Azul)</option>
                    <option value="success" class="text-success">Success (Verde)</option>
                    <option value="warning" class="text-warning">Warning (Amarelo)</option>
                    <option value="danger" class="text-danger">Danger (Vermelho)</option>
                    <option value="info" class="text-info">Info (Azul Claro)</option>
                    <option value="dark" class="text-dark">Dark (Preto)</option>
                </select>
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="createStatusForm" class="btn btn-primary">Salvar</button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- MODAL EDITAR --}}
    <x-ui.modal id="editStatusModal" title="Editar Status">
        <form method="POST" id="editStatusForm">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="edit_name" class="form-label">Nome do Status</label>
                <input type="text" class="form-control" name="name" id="edit_name" required>
            </div>
            <div class="mb-3">
                <label for="edit_color" class="form-label">Cor</label>
                <select name="color" id="edit_color" class="form-select" required>
                    <option value="secondary">Secondary</option>
                    <option value="primary">Primary</option>
                    <option value="success">Success</option>
                    <option value="warning">Warning</option>
                    <option value="danger">Danger</option>
                    <option value="info">Info</option>
                    <option value="dark">Dark</option>
                </select>
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="editStatusForm" class="btn btn-primary">Atualizar</button>
        </x-slot:footer>
    </x-ui.modal>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.btn-edit');
            const editForm = document.getElementById('editStatusForm');
            const nameInput = document.getElementById('edit_name');
            const colorInput = document.getElementById('edit_color');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    // Popula o formulário com os dados do botão clicado
                    editForm.action = this.dataset.url;
                    nameInput.value = this.dataset.name;
                    colorInput.value = this.dataset.color;
                });
            });
        });
    </script>
</x-app-layout>