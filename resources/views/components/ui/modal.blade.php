@props([
    'id',                     // O ID do modal, essencial para o data-bs-target
    'title',                  // O texto que aparecerá no cabeçalho
    'size' => '',             // Opcional: 'sm', 'lg', 'xl' para controlar o tamanho
])

@php
    $sizeClass = match($size) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        default => '',
    };
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $sizeClass }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- O corpo do modal é o slot padrão --}}
            <div class="modal-body">
                {{ $slot }}
            </div>

            {{-- O rodapé é um slot nomeado, para dar total controle sobre os botões --}}
            @if (isset($footer))
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>