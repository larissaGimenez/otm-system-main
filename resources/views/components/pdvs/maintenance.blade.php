@props(['pdv', 'maintenanceRequests'])

<x-slot:header>
    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb breadcrumb-custom px-3">
            <li class="breadcrumb-item">
                <a href="#" class="text-decoration-none"><i class="bi bi-house-door"></i> Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Chamados</li>
        </ol>
    </nav>
</x-slot:header>

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <i class="bi bi-tools me-2"></i>
            Histórico de Manutenções
        </h5>
        <span class="badge bg-primary rounded-pill">{{ $maintenanceRequests->count() }} chamados</span>
    </div>

    @if($maintenanceRequests->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-tools display-1 text-muted"></i>
            <h5 class="mt-3 text-muted">Nenhuma manutenção registrada</h5>
            <p class="text-muted">Não há chamados de manutenção para este PDV.</p>
        </div>
    @else
        <div class="row g-3">
            @foreach($maintenanceRequests as $request)
                <div class="col-12">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">
                                        <a href="{{ route('requests.show', $request) }}" class="text-decoration-none text-dark">
                                            {{ $request->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        Aberto em {{ $request->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $request->status->colors() }} rounded-pill">
                                    {{ $request->status->getLabel() }}
                                </span>
                            </div>

                            {{-- Descrição --}}
                            @if($request->description)
                                <p class="text-muted small mb-3">{{ Str::limit($request->description, 150) }}</p>
                            @endif

                            {{-- Informações de Fechamento --}}
                            @if($request->status === \App\Enums\Request\RequestStatus::COMPLETED && $request->closure_description)
                                <div class="border-top pt-3 mt-3">
                                    <h6 class="text-success small mb-2">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        Solução Aplicada
                                    </h6>
                                    <p class="small mb-2">{{ $request->closure_description }}</p>

                                    {{-- Mídia de Fechamento --}}
                                    @if($request->closure_media_path)
                                        <div class="mt-2">
                                            @if($request->closure_media_type === 'photo')
                                                <a href="{{ Storage::url($request->closure_media_path) }}" target="_blank">
                                                    <img src="{{ Storage::url($request->closure_media_path) }}" alt="Foto da solução"
                                                        class="img-thumbnail" style="max-height: 150px; cursor: pointer;">
                                                </a>
                                            @else
                                                <video controls class="rounded" style="max-height: 150px; max-width: 100%;">
                                                    <source src="{{ Storage::url($request->closure_media_path) }}" type="video/mp4">
                                                    Seu navegador não suporta vídeos.
                                                </video>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="text-muted small mt-2">
                                        <i class="bi bi-person-check me-1"></i>
                                        Fechado por <strong>{{ $request->closedBy->name }}</strong> em
                                        <strong>{{ $request->closed_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                </div>
                            @endif

                            {{-- Footer --}}
                            <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="bi bi-person me-1"></i>
                                    Solicitante: <strong>{{ $request->requester->name }}</strong>
                                </div>
                                <a href="{{ route('requests.show', $request) }}" class="btn btn-sm btn-outline-primary">
                                    Ver Detalhes
                                    <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>