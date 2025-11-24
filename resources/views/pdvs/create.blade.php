<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <div class="row align-items-start g-2">
                <div class="col-12">
                    <h2 class="fw-bold mb-1 text-break text-wrap fs-5 fs-md-4 fs-lg-3">
                        Cadastrar Novo Ponto de Venda
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <x-ui.flash-message />

    <div class="container-fluid">
        <div class="bg-white p-4 p-md-5 shadow-sm rounded">

            <form method="POST" action="{{ route('pdvs.store') }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Opa!</strong> Verifique os campos abaixo.
                    </div>
                @endif

                <h5 class="mb-4 border-bottom pb-2 font-weight-bold small text-uppercase text-muted">
                    Dados Principais
                </h5>

                {{-- Código do PDV --}}
                <div class="row mb-3">
                    <label for="name" class="col-md-2 col-form-label">
                        Código do PDV <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-6">
                        <input type="text" 
                               id="name" 
                               name="name"
                               value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               required>

                        @error('name') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror

                        <div id="name-feedback" class="small mt-1"></div>
                    </div>
                </div>

                {{-- Cliente --}}
                <div class="row mb-3">
                    <label for="client_id" class="col-md-2 col-form-label">
                        Cliente <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-6">
                        <select id="client_id" name="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
                            @foreach($initialClients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="row mb-3">
                    <label for="pdv_status_id" class="col-md-2 col-form-label">
                        Status <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-6">
                        <select id="pdv_status_id" 
                                name="pdv_status_id" 
                                class="form-select @error('pdv_status_id') is-invalid @enderror" 
                                required>
                            <option value="" disabled selected>Selecione um status...</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ old('pdv_status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('pdv_status_id') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>

                {{-- Observações --}}
                <div class="row mb-3">
                    <label for="description" class="col-md-2 col-form-label">Observações</label>
                    <div class="col-md-6">
                        <textarea id="description" name="description" 
                                  rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Insira o endereço e outras informações relevantes...">{{ old('description') }}</textarea>

                        @error('description') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror

                        <div class="form-text">Informações adicionais e localização.</div>
                    </div>
                </div>

                {{-- Botões --}}
                <div class="row mt-5">
                    <div class="col-md-6 offset-md-2">
                        <a href="{{ route('pdvs.index') }}" class="btn btn-outline-secondary me-2">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Salvar Ponto de Venda
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    @push('scripts')
    {{-- Verificação instantânea do Código do PDV --}}
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let timer = null;
        const input = document.getElementById("name");
        const feedback = document.getElementById("name-feedback");

        input.addEventListener("input", function () {
            clearTimeout(timer);
            const name = this.value.trim();
            feedback.innerHTML = "";

            if (name.length < 3) return;

            timer = setTimeout(() => {
                fetch(`{{ route('pdvs.check-name') }}?name=${encodeURIComponent(name)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            feedback.innerHTML = "<span class='text-danger'>Este código já está em uso.</span>";
                        } else {
                            feedback.innerHTML = "<span class='text-success'>Código disponível ✓</span>";
                        }
                    });
            }, 350);
        });
    });
    </script>

    {{-- Select inteligente (TomSelect) com resultados iniciais + busca incremental --}}
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        new TomSelect("#client_id", {
            valueField: "id",
            labelField: "name",
            searchField: "name",

            // Mostra clientes iniciais
            options: [
                @foreach($initialClients as $client)
                    { id: "{{ $client->id }}", name: "{{ $client->name }}" },
                @endforeach
            ],

            load: function(query, callback) {
                fetch("{{ route('clients.search') }}?q=" + encodeURIComponent(query))
                    .then(res => res.json())
                    .then(callback)
                    .catch(() => callback());
            },

            maxOptions: 50,
            placeholder: "Selecione um cliente...",
        });
    });
    </script>
    @endpush
</x-app-layout>
