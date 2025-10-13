@props([
    'collection',
    'searchRoute',
    'createRoute',
    'createText',
    'searchPlaceholder' => 'Buscar...',
    'deleteModalText' => 'Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.'
])

<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ $header }}
        </h2>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                <form action="{{ $searchRoute }}" method="GET">
                    <div class="input-group input-group-sm"> 
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="{{ $searchPlaceholder }}" 
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary btn-sm" type="submit"> 
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <a href="{{ $createRoute }}" class="btn btn-primary btn-sm mt-2 mt-md-0">
                    {{ $createText }}
                </a>
            </div>

            @if ($collection->isNotEmpty())
                
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle">
                        <thead class="border-bottom">
                            {{ $tableHeader }}
                        </thead>
                        <tbody>
                            {{ $slot }}
                        </tbody>
                    </table>
                </div>

                <div class="d-md-none">
                    {{ $mobileList }}
                </div>
            
            @else
                {{ $emptyState }}
            @endif

            @if ($collection->hasPages())
                <div class="mt-4">
                    {{ $collection->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ $deleteModalText }}
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

            document.querySelectorAll('tbody tr[data-href]').forEach(row => {
                row.addEventListener('click', function(event) {
                    if (event.target.closest('a, button')) {
                        return;
                    }
                    window.location.href = this.dataset.href;
                });
                row.style.cursor = 'pointer';
            });
        </script>
    @endpush
</x-app-layout>