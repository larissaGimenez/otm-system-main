<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Client;
use App\Enums\Client\ClientType;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $type = '';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingType() { $this->resetPage(); }

    public function clearFilters()
    {
        $this->reset(['search', 'type']);
        $this->resetPage();
    }

    public function with(): array
    {
        $query = Client::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('cnpj', 'like', "%{$this->search}%");
            });
        }

        if ($this->type) {
            $query->where('type', $this->type);
        }

        return [
            'clients' => $query->orderBy('name')->paginate(10),
            'types' => ClientType::cases(),
        ];
    }

    public function deleteClient($id)
    {
        Client::findOrFail($id)->delete();
        // O Livewire atualizará a lista automaticamente
    }
}; ?>

<div>
    {{-- CSS LOCAL - PADRÃO TASK BUCKET --}}
    <style>
        .display-6 { font-weight: 800; letter-spacing: -1.5px; }
        .rounded-4 { border-radius: 1rem !important; }
        .search-pill { border-radius: 50rem; border: 1px solid #eee; transition: all 0.2s; background: white; }
        .search-pill:focus-within { border-color: #4080f6; box-shadow: 0 0 0 0.25rem rgba(64, 128, 246, 0.1); }
        .tracking-widest { letter-spacing: 2px; }
        .btn-white { background: #fff; border: 1px solid #eee; }
    </style>

    {{-- INJEÇÃO DO BREADCRUMB NO HEADER DO APP.BLADE --}}
    <x-slot name="header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-custom mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none opacity-50 text-dark">Home</a></li>
                <li class="breadcrumb-item active text-dark fw-bold">Clientes</li>
            </ol>
        </nav>
    </x-slot>

    <div class="container-fluid px-2">
        {{-- 1. TÍTULO E BOTÃO NOVO --}}
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="d-block text-uppercase fw-bold text-muted tracking-widest mb-1" style="font-size: 0.7rem;">
                    <i class="bi bi-circle-fill text-success small me-1"></i> CARTEIRA DE CLIENTES
                </span>
                <h1 class="display-6 mb-0 text-dark">CLIENTES</h1>
            </div>

            <a href="{{ route('clients.create') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> NOVO CLIENTE
            </a>
        </div>

        {{-- 2. BARRA DE BUSCA REATIVA (ESTILO CÁPSULA) --}}
        <div class="shadow-sm search-pill p-2 mb-4 d-flex align-items-center px-3">
            <div class="d-flex flex-grow-1 align-items-center">
                <i class="bi bi-search text-muted ms-2"></i>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       class="form-control border-0 shadow-none bg-transparent ps-3 fw-bold text-muted small text-uppercase" 
                       placeholder="{{ $clients->total() }} CLIENTES ENCONTRADOS..."
                       style="letter-spacing: 1px;">
            </div>

            <div class="vr mx-3 opacity-10"></div>

            <button class="btn btn-transparent border-0 text-muted fw-bold d-flex align-items-center gap-2 px-3 position-relative" 
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters">
                <i class="bi bi-filter-right fs-5"></i>
                <span class="small text-uppercase">Filtrar</span>
                @php $activeCount = collect(['type' => $type])->filter()->count(); @endphp
                @if($activeCount > 0)
                    <span class="badge rounded-pill bg-primary ms-1" style="font-size: 0.6rem;">{{ $activeCount }}</span>
                @endif
            </button>
        </div>

        {{-- 3. PAINEL DE DADOS (CARD BRANCO) --}}
        <div class="bg-white shadow-sm rounded-4 overflow-hidden border">
            
            {{-- ÁREA DE FILTROS COLAPSÁVEL --}}
            <div class="collapse @if($activeCount > 0) show @endif" id="collapseFilters" wire:ignore.self>
                <div class="bg-light border-bottom p-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-muted fw-bold text-uppercase me-2" style="font-size: 0.65rem;">Tipo:</span>
                        
                        <button wire:click="$set('type', '')" 
                                class="btn btn-sm {{ empty($type) ? 'btn-dark text-white' : 'btn-outline-secondary border-0' }} rounded-pill px-3">
                            Todos
                        </button>

                        @foreach($types as $clientType)
                            <button wire:click="$set('type', '{{ $clientType->value }}')" 
                                    class="btn btn-sm {{ $type === $clientType->value ? 'btn-primary text-white' : 'btn-outline-secondary border-0' }} rounded-pill px-3">
                                {{ $clientType->name }}
                            </button>
                        @endforeach

                        @if($search || $type)
                            <button wire:click="clearFilters" class="btn btn-link btn-sm text-decoration-none text-muted fw-bold ms-auto" style="font-size: 0.7rem;">
                                <i class="bi bi-x-circle me-1"></i> LIMPAR TUDO
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TABELA DE CLIENTES --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light border-bottom text-uppercase">
                        <tr class="text-muted small fw-bold tracking-widest">
                            <th scope="col" class="py-3 ps-4">Nome</th>
                            <th scope="col" class="py-3">CNPJ</th>
                            <th scope="col" class="py-3">Tipo</th>
                            <th scope="col" class="py-3 text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clients as $client)
                            <tr wire:key="{{ $client->id }}" style="cursor: pointer;" onclick="window.location='{{ route('clients.show', $client) }}'">
                                <td class="py-3 ps-4 fw-bold text-dark">{{ $client->name }}</td>
                                <td class="py-3 text-muted small">{{ $client->cnpj }}</td>
                                <td class="py-3">
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary px-3 fw-bold tracking-wider" style="font-size: 0.6rem;">
                                        {{ strtoupper($client->type->name) }}
                                    </span>
                                </td>
                                <td class="py-3 text-end pe-4" onclick="event.stopPropagation();">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('clients.edit', $client) }}" class="btn btn-white btn-sm rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-pencil-fill text-primary small"></i>
                                        </a>
                                        <button wire:click="deleteClient('{{ $client->id }}')" 
                                                wire:confirm="Tem certeza que deseja excluir este cliente?"
                                                class="btn btn-white btn-sm rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-trash-fill text-danger small"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="bi bi-search display-1 opacity-10 d-block mb-3"></i>
                                    Nenhum cliente encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($clients->hasPages())
                <div class="p-4 border-top">
                    {{ $clients->links() }}
                </div>
            @endif
        </div>
    </div>
</div>