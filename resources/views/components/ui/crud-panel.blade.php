@props([
    'title',
    'buttonText',
    'createModalTargetId',
    'records' => [],
    'columns' => [],
    'emptyStateMessage' => 'Nenhum registro encontrado.',
])

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
            <h6 class="card-title text-muted small text-uppercase mb-2 mb-md-0">
                {{ $title }} ({{ $records->count() }})
            </h6>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#{{ $createModalTargetId }}">
                <i class="bi bi-plus-circle me-1"></i> {{ $buttonText }}
            </button>
        </div>

        @if ($records->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="bi bi-link-45deg fs-2 d-block mb-2"></i>
                <p>{{ $emptyStateMessage }}</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-muted small">
                        <tr>
                            @foreach ($columns as $column)
                                <th class="py-3">{{ $column['label'] }}</th>
                            @endforeach
                            <th class="py-3 text-end">Ações</th>
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