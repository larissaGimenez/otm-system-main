<?php

use Livewire\Volt\Component;
use App\Models\Client;
use App\Enums\Client\ClientType;
use App\Enums\General\GeneralBanks;
use App\Enums\General\GeneralPixType;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

new class extends Component {
    // Propriedades do Formulário
    public $name = '';
    public $cnpj = '';
    public $type = '';
    public $postal_code = '';
    public $street = '';
    public $number = '';
    public $complement = '';
    public $neighborhood = '';
    public $city = '';
    public $state = '';
    public $bank = '';
    public $agency = '';
    public $account = '';
    public $pix_type = '';
    public $pix_key = '';

    // Busca de CEP Reativa (ViaCEP no PHP)
    public function updatedPostalCode($value)
    {
        $cep = preg_replace('/\D/', '', $value);
        if (strlen($cep) !== 8) return;

        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
        
        if ($response->successful() && !isset($response['erro'])) {
            $data = $response->json();
            $this->street = $data['logradouro'];
            $this->neighborhood = $data['bairro'];
            $this->city = $data['localidade'];
            $this->state = $data['uf'];
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(array_column(ClientType::cases(), 'value'))],
            'cnpj' => ['required', 'string', 'max:18', 'unique:clients,cnpj'], // Máximo 18 para aceitar máscara se necessário
            'postal_code' => 'nullable|string|max:9',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:50',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'bank' => ['nullable', Rule::in(array_column(GeneralBanks::cases(), 'value'))],
            'agency' => 'nullable|string|max:20',
            'account' => 'nullable|string|max:20',
            'pix_type' => ['nullable', Rule::in(array_column(GeneralPixType::cases(), 'value'))],
            'pix_key' => 'nullable|string|max:255',
        ]);

        // Limpeza de máscaras antes de salvar
        $validated['cnpj'] = preg_replace('/\D/', '', $this->cnpj);
        $validated['postal_code'] = preg_replace('/\D/', '', $this->postal_code);

        Client::create($validated);

        session()->flash('success', 'Cliente cadastrado com sucesso!');
        return $this->redirect(route('clients.index'), navigate: true);
    }

    public function with()
    {
        return [
            'types' => ClientType::cases(),
            'banks' => GeneralBanks::cases(),
            'pixTypes' => GeneralPixType::cases(),
        ];
    }
}; ?>

<div class="py-4">
    <style>
        .display-6 { font-weight: 800; letter-spacing: -1.5px; }
        .rounded-4 { border-radius: 1rem !important; }
        .tracking-widest { letter-spacing: 2px; }
    </style>

    <div class="container-fluid px-4">
        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-end mb-4 px-2">
            <div>
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb small mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none opacity-50 text-dark">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}" class="text-decoration-none opacity-50 text-dark">Clientes</a></li>
                        <li class="breadcrumb-item active text-dark fw-bold">Novo Cliente</li>
                    </ol>
                </nav>
                <h1 class="display-6 mb-0 text-dark">CADASTRAR CLIENTE</h1>
            </div>
            <a href="{{ route('clients.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-bold border shadow-sm">
                VOLTAR
            </a>
        </div>

        <div class="bg-white p-4 p-md-5 shadow-sm rounded-4 border">
            <form wire:submit="save">
                
                {{-- IDENTIFICAÇÃO --}}
                <h5 class="mb-4 border-bottom pb-2 fw-bold small text-uppercase text-muted tracking-widest">
                    Identificação
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted text-uppercase">Nome (Razão Social) <span class="text-danger">*</span></label>
                        <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="Ex: Boxfarma LTDA">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">CNPJ <span class="text-danger">*</span></label>
                        <input type="text" wire:model="cnpj" id="cnpj" class="form-control @error('cnpj') is-invalid @enderror" placeholder="00.000.000/0000-00">
                        @error('cnpj') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Tipo de Cliente <span class="text-danger">*</span></label>
                        <select wire:model="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="">Selecione...</option>
                            @foreach ($types as $t)
                                <option value="{{ $t->value }}">{{ $t->getLabel() }}</option>
                            @endforeach
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- ENDEREÇO --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 fw-bold small text-uppercase text-muted tracking-widest">
                    Endereço
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">CEP</label>
                        <input type="text" wire:model.blur="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" placeholder="00000-000">
                        @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-muted text-uppercase">Rua / Avenida</label>
                        <input type="text" wire:model="street" class="form-control" placeholder="Rua...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Número</label>
                        <input type="text" wire:model="number" class="form-control" placeholder="123">
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Bairro</label>
                        <input type="text" wire:model="neighborhood" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Cidade</label>
                        <input type="text" wire:model="city" class="form-control">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label small fw-bold text-muted text-uppercase">UF</label>
                        <input type="text" wire:model="state" class="form-control" maxlength="2">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Complemento</label>
                        <input type="text" wire:model="complement" class="form-control">
                    </div>
                </div>

                {{-- DADOS BANCÁRIOS --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 fw-bold small text-uppercase text-muted tracking-widest">
                    Dados Bancários
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Banco</label>
                        <select wire:model="bank" class="form-select">
                            <option value="">Selecione...</option>
                            @foreach ($banks as $b)
                                <option value="{{ $b->value }}">{{ $b->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Agência</label>
                        <input type="text" wire:model="agency" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Conta</label>
                        <input type="text" wire:model="account" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Chave PIX</label>
                        <select wire:model="pix_type" class="form-select">
                            <option value="">Tipo...</option>
                            @foreach ($pixTypes as $pt)
                                <option value="{{ $pt->value }}">{{ $pt->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted text-uppercase">Chave</label>
                        <input type="text" wire:model="pix_key" class="form-control">
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top d-flex justify-content-end gap-2">
                    <a href="{{ route('clients.index') }}" class="btn btn-light px-5 py-2 rounded-pill fw-bold border">CANCELAR</a>
                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-bold shadow-sm">
                        SALVAR CLIENTE
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script de Máscaras (IMask) --}}
    @script
    <script>
        const setupMasks = () => {
            const cnpjEl = document.getElementById('cnpj');
            const cepEl = document.getElementById('postal_code');
            
            if (cnpjEl) IMask(cnpjEl, { mask: '00.000.000/0000-00' });
            if (cepEl) IMask(cepEl, { mask: '00000-000' });
        }
        
        setupMasks();
        // Recarregar máscaras após renderizações do Livewire
        Livewire.hook('morph.updated', (el, component) => { setupMasks(); });
    </script>
    @endscript
</div>