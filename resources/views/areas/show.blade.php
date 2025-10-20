<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Detalhes da Área
        </h2>
    </x-slot>

    <x-ui.flash-message />

    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            {{-- O x-data inicia o Alpine.js para todos os componentes filhos --}}
            <div x-data>
                <x-areas.context-header :area="$area" />

                <hr>

                <x-areas.tab-navigation :area="$area" />

                <div class="tab-content" id="areas-details-tabContent">
                    
                    <x-tab-content-wrapper id="details-tab-pane" :active="true">
                        <x-areas.details :area="$area" />
                    </x-tab-content-wrapper>

                    <x-tab-content-wrapper id="teams-tab-pane">
                        <x-areas.teams :area="$area" />
                    </x-tab-content-wrapper>

                    <x-tab-content-wrapper id="logs-tab-pane">
                        <x-areas.history :area="$area" />
                    </x-tab-content-wrapper>

                </div>
            </div>
        </div>
    </div>

    {{-- Modais da Página --}}
    <x-areas.modals.add-team :area="$area" :availableTeams="$availableTeams" />

    <x-slot name="scripts">
        <script>
            // A lógica de interatividade agora vive dentro dos componentes.
        </script>
    </x-slot>
</x-app-layout>