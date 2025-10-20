@props([
    'title' => 'Itens Associados',
    'buttonText' => 'Associar Novo Item',
    'modalTargetId' => '',
    'records' => [],
    'columns' => [],
    'emptyStateMessage' => 'Nenhum item encontrado.',
])

{{-- A CORREÇÃO ESTÁ AQUI --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <h6 class="card-title text-muted small text-uppercase mb-2 mb-md-0">
                {{ $title }} ({{ $records->count() }})
            </h6>

            @if($modalTargetId)
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="{{ $modalTargetId }}">
                    <i class="bi bi-plus-circle-fill me-1"></i> {{ $buttonText }}
                </button>
            @endif
        </div>

        @if ($records->isEmpty())
            <div class="text-center text-muted py-4">
                {{ $emptyStateMessage }}
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="border-bottom">
                        <tr class="text-muted small">
                            @foreach ($columns as $column)
                                <th class="py-3">{{ $column }}</th>
                            @endforeach
                            <th class="py-3 text-end">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{ $slot }}
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>