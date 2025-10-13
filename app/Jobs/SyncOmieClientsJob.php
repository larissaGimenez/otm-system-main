<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\OmieClient;
use App\Services\OmieApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\ClientSyncProgressUpdated;

class SyncOmieClientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $companyId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $companyId)
    {
        $this->companyId = $companyId;
    }

    public function handle(): void
    {
        $company = Company::find($this->companyId);
        if (! $company) {
            $this->fail('Empresa com ID ' . $this->companyId . ' não encontrada.');
            return;
        }

        $omieService = new OmieApiService($company->origem_app_key, $company->origem_app_secret);

        try {
            // CORREÇÃO: Chamamos o novo método para pegar os totais
            $initialData = $omieService->getFirstClientsPage();
            $totalRecords = $initialData['total_de_registros'] ?? 0;
            $processedCount = 0;

            if ($totalRecords === 0) {
                ClientSyncProgressUpdated::dispatch($this->companyId, 100);
                return;
            }

            // Dispara o evento inicial
            ClientSyncProgressUpdated::dispatch($this->companyId, 0);

            $omieService->processClientsInChunks(function ($clientsChunk) use ($company, &$processedCount, $totalRecords) {
                foreach ($clientsChunk as $clientData) {
                    OmieClient::updateOrCreate(
                        [
                            'company_id' => $company->id,
                            'codigo_cliente_omie' => $clientData['codigo_cliente_omie'],
                        ],
                        [
                            'codigo_cliente_integracao' => $clientData['codigo_cliente_integracao'] ?? null,
                            'razao_social' => $clientData['razao_social'] ?? 'Não informado',
                            'cnpj_cpf' => $clientData['cnpj_cpf'] ?? null,
                            'nome_fantasia' => $clientData['nome_fantasia'] ?? null,
                            'email' => $clientData['email'] ?? null,
                        ]
                    );
                }

                $processedCount += count($clientsChunk);
                $progress = ($totalRecords > 0) ? (int) (($processedCount / $totalRecords) * 100) : 0;

                ClientSyncProgressUpdated::dispatch($this->companyId, $progress);
            });

            ClientSyncProgressUpdated::dispatch($this->companyId, 100);

        } catch (\Exception $e) {
            // Se qualquer erro acontecer, falhamos o job e logamos a exceção
            $this->fail($e);
        }
    }
}