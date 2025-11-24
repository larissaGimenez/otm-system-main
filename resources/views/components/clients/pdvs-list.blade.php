@props([
    'client',
    'pdvs' => collect(),
    'availablePdvs' => collect(),
])

<div class="mt-4 card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="card-title text-muted small text-uppercase m-0">
                Pontos de Venda Associados
            </h6>

            <button class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAttachPdv">
                <i class="bi bi-plus-lg me-1"></i> Associar PDV
            </button>
        </div>

        @if ($pdvs->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-shop fs-2 d-block mb-2"></i>
                Nenhum Ponto de Venda associado a este cliente.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="text-muted small">
                        <tr>
                            <th scope="col">Codigo do PDV</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pdvs as $pdv)
                            <tr>
                                <td class="fw-bold">{{ $pdv->name }}</td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $pdv->status->color }}">
                                        {{ $pdv->status->name }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    <a href="{{ route('pdvs.show', $pdv) }}"
                                       class="btn btn-sm btn-outline-primary me-1"
                                       title="Ver Detalhes">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    <form action="{{ route('clients.pdvs.detach', [$client, $pdv]) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Remover este PDV do cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Desassociar">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Modal de associação --}}
<div class="modal fade" id="modalAttachPdv" tabindex="-1" aria-labelledby="modalAttachPdvLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('clients.pdvs.attach', $client) }}" class="modal-content">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="modalAttachPdvLabel">Associar PDV ao Cliente</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>

        <div class="modal-body">
            @if ($availablePdvs->isEmpty())
                <div class="alert alert-info mb-0">
                    Não há PDVs disponíveis (sem cliente) para associação.
                    <a href="{{ route('pdvs.create') }}" class="alert-link">Criar novo PDV</a>.
                </div>
            @else
                <div class="mb-3">
                    <label for="pdv_id" class="form-label">Selecione o PDV</label>

                    <input type="text" 
                           class="form-control form-control-sm mb-2"
                           placeholder="Filtrar por nome..."
                           oninput="(function(i){
                               const q = i.value.toLowerCase();
                               document.querySelectorAll('#pdvOptions option').forEach(
                                   o => o.hidden = !o.text.toLowerCase().includes(q)
                               );
                           })(this)">

                    <select id="pdv_id" name="pdv_id" class="form-select" required size="8">
                        <optgroup id="pdvOptions" label="PDVs disponíveis">
                            @foreach ($availablePdvs as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            @endif
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary" {{ $availablePdvs->isEmpty() ? 'disabled' : '' }}>
                <i class="bi bi-link-45deg me-1"></i> Associar
            </button>
        </div>
    </form>
  </div>
</div>
