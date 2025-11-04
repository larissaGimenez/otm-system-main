<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Detalhes do Equipamento
        </h2>
    </x-slot>

    {{-- 1. Componente de Flash Message (como no PDV) --}}
    <x-ui.flash-message />

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            {{-- 2. Componente de Cabeçalho de Contexto --}}
            <x-equipments.context-header :equipment="$equipment" />

            <hr>

            {{-- 3. Componente de Navegação das Abas --}}
            @php
                $photoCount = is_array($equipment->photos) ? count($equipment->photos) : 0;
            @endphp
            <x-equipments.tab-navigation :equipment="$equipment" :photoCount="$photoCount" />

            {{-- 4. Conteúdo das Abas (usando o wrapper genérico) --}}
            <div class="tab-content" id="equipments-details-tabContent">
                
                <x-tab-content-wrapper id="details-tab-pane" :active="true">
                    <x-equipments.details :equipment="$equipment" />
                </x-tab-content-wrapper>

                <x-tab-content-wrapper id="gallery-tab-pane">
                    <x-equipments.gallery :equipment="$equipment" />
                </x-tab-content-wrapper>
                
                <x-tab-content-wrapper id="history-tab-pane">
                    <x-equipments.history :equipment="$equipment" />
                </x-tab-content-wrapper>

            </div>
        </div>
    </div>

    <x-slot name="scripts">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hash = window.location.hash;
            const targetId = hash ? hash + '-pane' : null;
            
            if (targetId) {
                const tabTrigger = document.querySelector(`button[data-bs-target="${targetId}"]`);

                if (tabTrigger) {
                    const tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }
        });
    </script>
    </x-slot>

</x-app-layout>