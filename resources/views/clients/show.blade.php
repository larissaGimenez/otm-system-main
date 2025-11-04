<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            Detalhes do Cliente
        </h2>
    </x-slot>

    <x-ui.flash-message />

    <div class="card shadow-sm border-0">
        <div class="card-body">
            

            {{-- 1. Cabeçalho de Contexto (Nome, CNPJ, Botões) --}}
            <x-clients.context-header :client="$client" />

            <hr>

            {{-- 2. Navegação das Abas (Carrega os PDVs para contagem) --}}
            @php
                // Carrega a relação 'pdvs' se ainda não estiver carregada
                $client->loadMissing('pdvs');
                $pdvCount = $client->pdvs->count();
            @endphp
            <x-clients.tab-navigation :client="$client" :pdvCount="$pdvCount" />

            {{-- 3. Conteúdo das Abas --}}
            <div class="tab-content" id="clients-details-tabContent">
                
                {{-- Aba de Detalhes --}}
                <x-tab-content-wrapper id="details-tab-pane" :active="true">
                    <x-clients.details :client="$client" />
                </x-tab-content-wrapper>

                {{-- Aba de PDVs --}}
                <x-tab-content-wrapper id="pdvs-tab-pane">
                    {{-- Passa a coleção de PDVs que já carregamos --}}
                    <x-clients.pdvs-list :client="$client" :pdvs="$pdvs" :availablePdvs="$availablePdvs" />
                </x-tab-content-wrapper>
                
                {{-- Aba de Histórico --}}
                <x-tab-content-wrapper id="history-tab-pane">
                    <x-clients.history :client="$client" />
                </x-tab-content-wrapper>

            </div>
        </div>
    </div>

    {{-- (Espaço para futuros Modais, se necessário) --}}

    {{-- 4. Script de navegação por Hash (idêntico ao de Equipamentos) --}}
    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
    </x-slot:scripts>

</x-app-layout>