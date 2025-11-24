<x-app-layout>
    <x-slot:header>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-sm mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Configurações</a></li>
                <li class="breadcrumb-item active" aria-current="page">Equipamentos</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0 fs-3">Configurações de Equipamentos</h2>
    </x-slot:header>

    <div class="container-fluid py-4">
        <div class="row g-4">

            <div class="col-md-6 col-lg-4">
                <a href="{{ route('settings.equipments.statuses.index') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body p-4 d-flex align-items-start">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="bi bi-circle-half text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Status de Equipamento</h5>
                                <p class="text-muted small mb-0">
                                    Defina estados como Disponível, Em uso, Manutenção, Danificado, etc.
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-end">
                            <span class="text-primary small fw-bold">
                                Acessar <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-4">
                <a href="{{ route('settings.equipments.types.index') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body p-4 d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="bi bi-hdd-network text-success fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Tipos de Equipamento</h5>
                                <p class="text-muted small mb-0">
                                    Crie categorias como Notebook, Impressora, Switch, Terminal, etc.
                                </p>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 p-4 pt-0 d-flex justify-content-end">
                            <span class="text-success small fw-bold">
                                Acessar <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>

    <style>
        .hover-shadow:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
</x-app-layout>
