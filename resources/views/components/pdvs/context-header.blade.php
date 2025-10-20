@props([
    'pdv',
])

{{-- 
    Chama o componente de UI genérico e fornece os dados e
    o HTML específico do PDV através dos slots.
--}}
<x-ui.page-context-header :title="$pdv->name">

    {{-- Preenche o slot 'status' com o badge de status do PDV --}}
    <x-slot name="status">
        <span class="badge rounded-pill {{ $pdv->status === 'Ativo' ? 'bg-success' : 'bg-secondary' }}">
            {{ $pdv->status }}
        </span>
    </x-slot>

    {{-- Preenche o slot 'actions' com os botões de Voltar e Editar --}}
    <x-slot name="actions">
        <a href="{{ route('pdvs.index') }}" class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
        <a href="{{ route('pdvs.edit', $pdv) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil-fill me-1"></i> Editar PDV
        </a>
    </x-slot>

</x-ui.page-context-header>