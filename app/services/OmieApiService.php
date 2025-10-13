<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Closure;

class OmieApiService
{
    /**
     * Propriedades da classe. Verifique se as suas estão
     * declaradas exatamente como abaixo.
     */
    protected string $appKey;
    protected string $appSecret;
    protected string $baseUrl = 'https://app.omie.com.br/api/v1/';

    public function __construct(string $appKey, string $appSecret)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
    }

    /**
     * Busca os clientes em lotes e executa uma ação para cada lote.
     */
    public function processClientsInChunks(Closure $callback): void
    {
        $page = 1;

        while (true) {
            // Acessando a propriedade com '$this->baseUrl'
            $response = Http::post($this->baseUrl . 'geral/clientes/', [
                'call' => 'ListarClientes',
                'app_key' => $this->appKey,
                'app_secret' => $this->appSecret,
                'param' => [['pagina' => $page, 'registros_por_pagina' => 50]]
            ]);

            if ($response->failed()) {
                throw new \Exception('Falha na comunicação com a API da Omie: ' . $response->body());
            }

            $data = $response->json();
            
            if (empty($data['clientes_cadastro'])) {
                break;
            }

            $callback($data['clientes_cadastro']);
            
            if (isset($data['total_de_paginas']) && $page >= $data['total_de_paginas']) {
                break;
            }

            $page++;
            sleep(1); 
        }
    }

    public function getFirstClientsPage(): array
    {
        $response = Http::post($this->baseUrl . 'geral/clientes/', [
            'call' => 'ListarClientes',
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
            'param' => [['pagina' => 1, 'registros_por_pagina' => 1]] // Só precisamos dos totais
        ]);
        return $response->json();
    }
}