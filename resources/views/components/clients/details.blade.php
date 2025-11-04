@props(['client'])

{{-- 
  Usando o componente x-ui.details-panel que você já possui 
  para exibir os dados em seções.
--}}
<x-ui.details-panel
    title="Dados do Cliente"
    :sections="[
        [
            'title' => 'Identificação',
            'rows' => [
                ['label' => 'Nome (Razão Social)', 'value' => $client->name],
                ['label' => 'CNPJ', 'value' => $client->cnpj],
                ['label' => 'Tipo', 'value' => $client->type->getLabel()],
            ],
        ],
        [
            'title' => 'Endereço',
            'rows' => [
                ['label' => 'CEP', 'value' => $client->postal_code],
                ['label' => 'Logradouro', 'value' => $client->street],
                ['label' => 'Número', 'value' => $client->number],
                ['label' => 'Complemento', 'value' => $client->complement],
                ['label' => 'Bairro', 'value' => $client->neighborhood],
                ['label' => 'Cidade/UF', 'value' => $client->city . ' / ' . $client->state],
            ],
        ],
        [
            'title' => 'Dados Bancários',
            'rows' => [
                ['label' => 'Banco', 'value' => $client->bank?->getLabel()],
                ['label' => 'Agência', 'value' => $client->agency],
                ['label' => 'Conta', 'value' => $client->account],
                ['label' => 'Tipo PIX', 'value' => $client->pix_type?->getLabel()],
                ['label' => 'Chave PIX', 'value' => $client->pix_key],
            ],
        ],
    ]"
/>