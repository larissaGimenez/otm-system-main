<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                    Meus Chamados e Fila
                </h2>
                
                <a href="{{ route('requests.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Abrir Chamado
                </a>
            </div>
        </div>
    </x-slot>

    <x-ui.flash-message />

    @livewire('requests-kanban')
    
</x-app-layout>