<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">Detalhes do Cliente</h2>
    </x-slot>

    <x-ui.flash-message />

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- 1) Cabeçalho de contexto --}}
            <x-clients.context-header :client="$client" />

            <hr>

            {{-- 2) Navegação das abas (usa contagens vindas do controller) --}}
            <x-clients.tab-navigation
                :client="$client"
                :pdvCount="$pdvCount"
                :contractCount="$contractCount"
                :contactCount="$contactCount"
                :installmentsCount="$installmentsCount"
            />

            {{-- 3) Conteúdo das abas --}}
            <div class="tab-content" id="clients-details-tabContent">

                {{-- Aba: Detalhes (ativa por padrão) --}}
                <x-tab-content-wrapper id="details-tab-pane" :active="true">
                    <x-clients.details :client="$client" />
                </x-tab-content-wrapper>

                <x-tab-content-wrapper id="contacts-tab-pane">
                    <x-clients.contacts :client="$client" />
                </x-tab-content-wrapper>

                {{-- Aba: PDVs --}}
                <x-tab-content-wrapper id="pdvs-tab-pane">
                    <x-clients.pdvs-list
                        :client="$client"
                        :pdvs="$client->pdvs"
                        :availablePdvs="$availablePdvs"
                    />
                </x-tab-content-wrapper>

                {{-- Aba: Contratos (com faturamentos por contrato) --}}
                <x-tab-content-wrapper id="contracts-tab-pane">
                    <x-clients.contracts :client="$client" />
                </x-tab-content-wrapper>

                {{-- Aba: Custo de Implantação (Activation Fee) --}}
                <x-tab-content-wrapper id="activation-fee-tab-pane">
                    <x-clients.activation-fee :client="$client" />
                </x-tab-content-wrapper>

                {{-- Aba: Histórico (seu componente existente) --}}
                <x-tab-content-wrapper id="history-tab-pane">
                    <x-clients.history :client="$client" />
                </x-tab-content-wrapper>

            </div>
        </div>
    </div>

    {{-- 4) Modais (de contrato, faturamento e activation fee) --}}
    {{-- Coloque-os aqui para ficarem disponíveis em qualquer aba --}}

    {{-- Activation Fee (criar/editar) --}}
    <x-clients.modals.create-activation-fee :client="$client" />
    @if($client->activationFee)
        <x-clients.modals.edit-activation-fee :client="$client" :fee="$client->activationFee" />
    @endif

    {{-- Contratos: criar --}}
    <x-clients.modals.create-contract :client="$client" />

    {{-- Contratos: editar + Faturamentos (por contrato) --}}
    @foreach($client->contracts as $contract)
        <x-clients.modals.edit-contract :contract="$contract" />
        <x-clients.modals.create-monthly-sale :contract="$contract" />

        @foreach($contract->monthlySales as $sale)
            <x-clients.modals.edit-monthly-sale :sale="$sale" />
        @endforeach
    @endforeach

    <x-clients.modals.create-contact :client="$client" />
    @foreach($client->contacts as $contact)
        <x-clients.modals.edit-contact :contact="$contact" />
    @endforeach

    {{-- 5) Script: ativar aba pela hash da URL (#pdvs, #contracts, etc.) --}}
    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const setInitialTabFromHash = () => {
                    const hash = window.location.hash;
                    if (!hash) return;
                    const trigger = document.querySelector(`button[data-bs-target="${hash}"]`);
                    if (trigger) new bootstrap.Tab(trigger).show();
                };

                setInitialTabFromHash();

                // quando mudar de aba, atualiza a hash (bom p/ compartilhar link direto)
                const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
                tabButtons.forEach(btn => {
                    btn.addEventListener('shown.bs.tab', e => {
                        const target = e.target?.getAttribute('data-bs-target');
                        if (target) history.replaceState({}, '', target);
                    });
                });
            });
        </script>
    </x-slot:scripts>
</x-app-layout>
