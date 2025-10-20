@props([
    'title' => null,
    'sections' => [],
    'emptyText' => 'Não informado',
])

{{-- 
    COMPONENTE DE APRESENTAÇÃO (UI)
    Note que não há nenhuma menção a 'PDV' ou qualquer outra entidade aqui.
    Ele é 100% reutilizável.
--}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($title)
            <h5 class="card-title mb-3">{{ $title }}</h5>
        @endif

        @foreach($sections as $sectionIndex => $section)
            @php
                $rows = $section['rows'] ?? [];
                $sectionTitle = $section['title'] ?? null;
            @endphp

            @if($sectionTitle)
                <h6 class="card-title text-muted small text-uppercase {{ $sectionIndex > 0 ? 'mt-4' : '' }}">
                    {{ $sectionTitle }}
                </h6>
            @endif

            @forelse($rows as $i => $row)
                @php
                    $label = $row['label'] ?? '';
                    $value = $row['value'] ?? null;
                    $isLast = $i === (count($rows) - 1);
                    $isEmpty = $value === null || (is_string($value) && trim($value) === '');
                @endphp
                <div class="row py-2 {{ !$isLast ? 'border-bottom' : '' }}">
                    <div class="col-md-3">
                        <strong>{{ $label }}</strong>
                    </div>
                    <div class="col-md-9">
                        {{ $isEmpty ? $emptyText : $value }}
                    </div>
                </div>
            @empty
                <div class="text-muted small">{{ $emptyText }}</div>
            @endforelse
        @endforeach

        {{ $slot }}
    </div>
</div>