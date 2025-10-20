@props([
    'title' => 'Logs de Atividade',
    'records' => [],
])

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="card-title text-muted small text-uppercase mb-3">
            {{ $title }}
        </h6>

        @forelse ($records as $record)
            <div class="d-flex align-items-start mb-3">
                <div class="me-3">
                    {{-- A sintaxe de objeto é mais robusta e compatível com Models --}}
                    <i class="bi {{ $record->icon ?? 'bi-info-circle' }} fs-4 {{ $record->color ?? 'text-muted' }}"></i>
                </div>
                <div>
                    {{-- Usamos {!! !!} para renderizar o HTML (ex: <strong>) na descrição --}}
                    <p class="mb-0 small">{!! $record->description !!}</p>
                    <small class="text-muted">
                        {{-- Usamos optional() para evitar erros se o timestamp ou causer forem nulos --}}
                        {{ optional($record->timestamp)->diffForHumans() }} por {{ $record->causer ?? 'Sistema' }}
                    </small>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-3">
                Nenhuma atividade registrada.
            </div>
        @endforelse
    </div>
</div>