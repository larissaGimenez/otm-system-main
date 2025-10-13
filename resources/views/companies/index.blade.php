<x-app-layout>
    @section('sidebar')
        @include('layouts._sidebar-admin')
    @endsection

    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="title-main">
                Empresas Cadastradas
            </h2>
            <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Cadastrar Nova Empresa
            </a>
        </div>
    </x-slot>

    <div class="py-4">

        @forelse ($companies as $company)
            <div class="card mb-3 shadow-sm border-0">
                {{-- MELHORIA: Layout interno do card para acomodar logo e mais informações --}}
                <div class="card-body d-flex justify-content-between align-items-center">
                    
                    {{-- Lado Esquerdo: Logo, Nome, CNPJ e Localização --}}
                    <div class="d-flex align-items-center">
                        {{-- Exibe a logo ou um ícone padrão --}}
                        @if ($company->logo_path)
                            <img src="{{ Storage::url($company->logo_path) }}" alt="Logo de {{ $company->name }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="rounded-circle me-3 bg-light d-flex justify-content-center align-items-center text-secondary" style="width: 50px; height: 50px;">
                                <i class="bi bi-building fs-4"></i>
                            </div>
                        @endif
                        
                        <div>
                            <h5 class="card-title fw-bold mb-0">{{ $company->name }}</h5>
                            {{-- ATUALIZAÇÃO: Exibindo o CNPJ --}}
                            <p class="card-text small text-muted mb-0">
                                CNPJ: {{ $company->cnpj }}
                            </p>
                            {{-- ATUALIZAÇÃO: Exibindo a localização --}}
                            @if($company->cidade || $company->estado)
                            <p class="card-text small text-muted">
                                <i class="bi bi-geo-alt-fill me-1"></i>
                                {{ $company->cidade ?? 'N/A' }} - {{ $company->estado ?? 'N/A' }}
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Lado Direito: Botões de Ação (Mantidos) --}}
                    <div>
                        <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-sm btn-outline-secondary me-1" title="Detalhes">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                        <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-outline-primary me-1" title="Editar">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal" 
                                data-action="{{ route('admin.companies.destroy', $company) }}" 
                                title="Excluir">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>

                </div>
            </div>
        @empty
            {{-- O "empty state" já está ótimo, nenhuma alteração necessária aqui --}}
            <div class="text-center py-5">
                <div class="card-body">
                    <i class="bi bi-building-slash display-1 text-muted"></i>
                    <h2 class="mt-4">Nenhuma Empresa Cadastrada</h2>
                    <p class="lead text-muted">
                        Clique no botão abaixo para adicionar a primeira empresa ao sistema.
                    </p>
                    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary btn-lg mt-3">
                        <i class="bi bi-plus-circle me-2"></i> Cadastrar Primeira Empresa
                    </a>
                </div>
            </div>
        @endforelse

        {{-- Paginação --}}
        <div class="mt-4">
            {{ $companies->links() }}
        </div>
    </div>

    {{-- O Modal de exclusão e o script já estão perfeitos, nenhuma alteração necessária --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja excluir esta empresa? Esta ação não pode ser desfeita.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const action = button.getAttribute('data-action');
                    const form = deleteModal.querySelector('#deleteForm');
                    form.setAttribute('action', action);
                });
            }
        </script>
    @endpush

</x-app-layout>