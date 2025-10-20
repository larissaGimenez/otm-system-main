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

            <x-pdvs.tab-navigation :pdv="$pdv" :externalIdCount="$externalIdRecords->count()" />

            <div x-data class="tab-content" id="pdvs-details-tabContent">
                
                <x-tab-content-wrapper id="details-tab-pane" :active="true">
                    <x-pdvs.details :pdv="$pdv" />
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
        });
    </script>
</x-slot>
</x-app-layout>