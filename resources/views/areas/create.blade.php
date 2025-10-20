<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Cadastrar Nova Área
        </h2>
    </x-slot>

    <x-ui.flash-message />

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('areas.store') }}" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
                @csrf

                <div class="row">
                    {{-- Campo Nome --}}
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Nome da Área</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Campo Descrição --}}
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Descrição (Opcional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    <a href="{{ route('areas.index') }}" class="btn btn-outline-secondary me-2">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>