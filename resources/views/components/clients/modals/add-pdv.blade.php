@props(['client', 'availablePdvs' => collect()])

<div class="modal fade" id="modalAddPdv" tabindex="-1" aria-labelledby="modalAddPdvLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('clients.pdvs.attach', $client) }}" class="modal-content">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title" id="modalAddPdvLabel">Associar Ponto de Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                @if ($availablePdvs->isEmpty())
                    <div class="alert alert-info mb-0">
                        Nenhum PDV disponível para associação no momento.
                        <a href="{{ route('pdvs.create') }}" class="alert-link">Criar novo PDV</a>.
                    </div>
                @else
                    <div class="mb-3">
                        <label for="pdv_id" class="form-label">Selecione o PDV</label>

                        {{-- Filtro rápido pelo nome --}}
                        <input type="text"
                               class="form-control form-control-sm mb-2"
                               placeholder="Filtrar por nome..."
                               oninput="(function(i){const q=i.value.toLowerCase();document.querySelectorAll('#pdvOptions option').forEach(o=>o.hidden = !o.text.toLowerCase().includes(q));})(this)">

                        <select id="pdv_id" name="pdv_id" class="form-select" size="8" required>
                            <optgroup id="pdvOptions" label="PDVs disponíveis">
                                @foreach ($availablePdvs as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->name }}
                                        @if($p->city)
                                            — {{ $p->city }}/{{ $p->state }}
                                        @endif
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
