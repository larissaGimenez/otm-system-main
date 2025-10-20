@props([
    'tabs' => [], // Espera um array de abas
])

{{-- 
    Renderiza a navegação de abas (nav-pills) do Bootstrap.
    É um componente de UI genérico e reutilizável.
--}}
<ul class="nav nav-pills mb-3" role="tablist">
    @foreach ($tabs as $tab)
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link @if($tab['active']) active @endif" 
                id="{{ $tab['id'] }}" 
                data-bs-toggle="pill" 
                data-bs-target="#{{ $tab['target'] }}" 
                type="button" 
                role="tab"
            >
                @if(isset($tab['icon']))
                    <i class="{{ $tab['icon'] }} me-1"></i> 
                @endif
                {{ $tab['label'] }}
            </button>
        </li>
    @endforeach
</ul>