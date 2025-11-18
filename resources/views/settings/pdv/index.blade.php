<x-app-layout>
    <x-slot:header>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb breadcrumb-sm mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Configurações</a></li>
                <li class="breadcrumb-item active" aria-current="page">PDV</li>
            </ol>
        </nav>
        <h2 class="fw-bold mb-0 fs-3">Configurações de PDV</h2>
    </x-slot:header>

    <div class="container-fluid py-4">
        <div class="row g-4">
            
            {{-- CARD 1: STATUS --}}
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('settings.pdv.statuses.index') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body p-4 d-flex align-items-start">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="bi bi-tags-fill text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Status de PDV</h5>
                                <p class="text-muted small mb-0">
                                    Gerencie os estados possíveis (Ativo, Inativo, Reforma) e suas cores de exibição.
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

            {{-- CARD 2: TIPOS --}}
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('settings.pdv.types.index') }}" class="text-decoration-none">
                    <div class="card h-100 shadow-sm border-0 hover-shadow transition-all">
                        <div class="card-body p-4 d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                                <i class="bi bi-shop text-success fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Tipos de PDV</h5>
                                <p class="text-muted small mb-0">
                                    Defina as categorias de pontos de venda (Quiosque, Loja de Rua, Shopping).
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

    {{-- Estilo Extra para o efeito de Hover (caso não tenha no seu CSS global) --}}
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