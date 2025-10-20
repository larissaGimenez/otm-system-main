@props([
    'title' => 'Histórico de Atividades',
    'records' => [], // Espera uma coleção de itens de log
])

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="card-title text-muted small text-uppercase mb-3">
            {{ $title }}
        </h6>

        @if ($records->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-clock-history fs-2 d-block mb-2"></i>
                <p>Nenhuma atividade registrada para este item ainda.</p>
            </div>
        @else
            <ul class="list-group list-group-flush">
                @foreach ($records as $record)
                    <li class="list-group-item px-0 d-flex">
                        {{-- Ícone do Evento --}}
                        <div class="me-3">
                            <i class="{{ $record['icon'] ?? 'bi-info-circle' }} fs-5 {{ $record['color'] ?? 'text-muted' }}"></i>
                        </div>
                        
                        {{-- Descrição e Data do Evento --}}
                        <div>
                            <div class="mb-1">{!! $record['description'] !!}</div>
                            <small class="text-muted">
                                {{ $record['timestamp']->format('d/m/Y \à\s H:i') }}
                                @if(isset($record['causer']))
                                    por {{ $record['causer'] }}
                                @endif
                            </small>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>