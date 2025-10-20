@props([
    'title',
])

{{-- 
    Componente de UI genérico para o cabeçalho de contexto de uma página.
    Usa slots para permitir flexibilidade no conteúdo secundário e nas ações.
--}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-4">
    <div>
        {{-- Título Principal --}}
        <h3 class="font-weight-bold mb-1">{{ $title }}</h3>

        {{-- Slot para conteúdo secundário, como status, subtítulo, etc. --}}
        @if (isset($status))
            <div class="text-muted mb-2">
                {{ $status }}
            </div>
        @endif
    </div>
    
    {{-- Slot para os botões de ação --}}
    @if (isset($actions))
        <div class="mt-3 mt-md-0">
            {{ $actions }}
        </div>
    @endif
</div>