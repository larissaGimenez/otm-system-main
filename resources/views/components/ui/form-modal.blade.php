@props([
    'id',
    'title',
    'formAction',
    'formMethod' => 'POST',
    'submitText' => 'Salvar',
    'size' => '',
    'enctype' => null,
])

@php
    $sizeClass = match($size) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        default => '', 
    };
    $htmlFormMethod = strtoupper($formMethod) === 'GET' ? 'GET' : 'POST';
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $sizeClass }}">
        <div class="modal-content">
            {{-- 1. Adicionamos x-data e @submit ao formulário --}}
            <form x-data="{ submitting: false }" @submit="submitting = true" action="{{ $formAction }}" method="{{ $htmlFormMethod }}" @if($enctype) enctype="{{ $enctype }}" @endif>
                @csrf
                @if ($htmlFormMethod !== strtoupper($formMethod))
                    @method($formMethod)
                @endif

                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{ $slot }}
                </div>

                <div class="modal-footer">
                    @if (isset($footer))
                        {{ $footer }}
                    @else
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" :disabled="submitting" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                            <span x-show="submitting" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            <span x-text="submitting ? 'Salvando...' : '{{ $submitText }}'"></span>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>