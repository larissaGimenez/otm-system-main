<x-app-layout>
    <x-slot:header>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-custom px-3">
                <li class="breadcrumb-item">
                    <a href="#" class="text-decoration-none"><i class="bi bi-house-door"></i> Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Chamados</li>
            </ol>
        </nav>
    </x-slot:header>

    <div class="container-fluid">
        {{-- Container Principal com padding para alinhar com os outros --}}

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <div class="d-flex align-items-center gap-3 ms-2">
                <h2 class="fw-bold mb-0 fs-3">Meus Chamados e Fila</h2>
                <a href="{{ route('requests.create') }}" class="btn btn-primary px-4 rounded-3 ms-2">
                    <i class="bi bi-plus-lg me-1"></i> Abrir Chamado
                </a>
            </div>
        </div>

        <x-ui.flash-message />

        <div style="height: calc(100vh - 250px); min-height: 600px;">
            @livewire('requests-kanban')
        </div>

    </div>
</x-app-layout>