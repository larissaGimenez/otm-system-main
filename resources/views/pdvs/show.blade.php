<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Detalhes do Ponto de Venda
        </h2>
    </x-slot>

    <x-ui.flash-message />

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            <x-pdvs.context-header :pdv="$pdv" />

            <hr>

            {{-- IMPORTANTE: Seu componente de navegação precisa gerar os botões com o atributo data-bs-target correspondente aos IDs abaixo. --}}
            {{-- Ex: <button data-bs-target="#gallery-tab-pane">...</button> --}}
            <x-pdvs.tab-navigation :pdv="$pdv" :externalIdCount="$externalIdRecords->count()" :contractCount="$contractCount" />

            <div x-data class="tab-content" id="pdvs-details-tabContent">
                
                <x-tab-content-wrapper id="details-tab-pane" :active="true">
                    <x-pdvs.details :pdv="$pdv" />
                </x-tab-content-wrapper>

                <x-tab-content-wrapper id="contracts-tab-pane">
                    <x-pdvs.contracts :pdv="$pdv" />
                </x-tab-content-wrapper>

                <x-tab-content-wrapper id="equipments-tab-pane">
                    <x-pdvs.equipments :pdv="$pdv" />
                </x-tab-content-wrapper>

                <x-tab-content-wrapper id="gallery-tab-pane">
                    <x-pdvs.gallery :pdv="$pdv" />
                </x-tab-content-wrapper>
                
                <x-tab-content-wrapper id="history-tab-pane">
                    <x-pdvs.history :pdv="$pdv" />
                </x-tab-content-wrapper>
                
                <x-tab-content-wrapper id="extids-tab-pane">
                    <x-pdvs.external-ids :pdv="$pdv" />
                </x-tab-content-wrapper>

            </div>
        </div>
    </div>

    @php
        $createModalId = "extidCreateModal_" . substr((string) $pdv->id, 0, 8);
    @endphp

    <x-pdvs.modals.add-equipment :pdv="$pdv" :availableEquipments="$availableEquipments" />
    <x-pdvs.modals.create-external-id :pdv="$pdv" :modalId="$createModalId" />

    @foreach ($externalIdRecords as $ext)
        <x-pdvs.modals.edit-external-id :ext="$ext" :pdv="$pdv" />
    @endforeach

    <x-pdvs.modals.create-contract :pdv="$pdv" />
    @foreach ($pdv->contracts as $contract)
        <x-pdvs.modals.edit-contract :contract="$contract" />
        <x-pdvs.modals.create-monthly-sale :contract="$contract" />
        @foreach ($contract->monthlySales as $sale)
            <x-pdvs.modals.edit-monthly-sale :sale="$sale" />
        @endforeach
    @endforeach

    <x-slot name="scripts">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if ($errors->any() && old('item_uuid') === $pdv->id)
                const modalEl = document.getElementById('{{ $createModalId }}');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            @endif

            const hash = window.location.hash;

            if (hash) {
                const tabTrigger = document.querySelector(`button[data-bs-target="${hash}"]`);

                if (tabTrigger) {
                    const tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }
        });
    </script>
</x-slot>
</x-app-layout>