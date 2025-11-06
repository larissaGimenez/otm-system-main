<?php

namespace App\Http\Controllers;

// Imports necessários para a lógica do CLIENTE
use App\Models\Client; 
use App\Models\ActivationFee;
use App\Models\FeeInstallment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Mova este 'use'

class ActivationFeeController extends Controller
{
    /**
     * Cria a Activation Fee de um cliente com as parcelas editáveis.
     * Esta é a lógica movida do ClientController.
     */
    public function store(Request $request, Client $client): RedirectResponse
    {
        // impede fee duplicada
        if ($client->activationFee) {
            return back()->with('error', 'Este cliente já possui um Custo de Implantação.');
        }

        $data = $request->validate([
            'total_value'           => ['required', 'numeric', 'min:0'],
            'installments_count'    => ['required', 'integer', 'min:1'],
            'first_due_date'        => ['required', 'date'],
            'notes'                 => ['nullable', 'string', 'max:2000'],

            // parcelas vindas do preview (todas opcionais; se ausentes, geramos automaticamente)
            'installments'          => ['array'],
            'installments.*.installment_number' => ['nullable', 'integer', 'min:1'],
            'installments.*.due_date'           => ['nullable', 'date'],
            'installments.*.amount'             => ['nullable', 'numeric', 'min:0'],
        ]);

        return DB::transaction(function () use ($client, $data) {

            $fee = ActivationFee::create([
                'client_id'   => $client->id,
                'total_value' => $data['total_value'],
                'notes'       => $data['notes'] ?? null,
            ]);

            // Monta as parcelas a partir do payload (ou gera automaticamente)
            $installments = $this->normalizeInstallmentsPayload(
                $data['installments'] ?? [],
                (int) $data['installments_count'],
                $data['total_value'],
                $data['first_due_date']
            );

            // (opcional) checar soma vs total e ajustar centavos na última
            $sum = collect($installments)->sum('amount');
            if (round($sum, 2) !== round($data['total_value'], 2)) {
                // ajusta diferença na última parcela para bater com o total
                $diff = round($data['total_value'] - $sum, 2);
                $lastIndex = count($installments) - 1;
                $installments[$lastIndex]['amount'] = round($installments[$lastIndex]['amount'] + $diff, 2);
            }

            // cria parcelas
            foreach ($installments as $row) {
                FeeInstallment::create([
                    'activation_fee_id' => $fee->id,
                    'installment_number'=> $row['installment_number'],
                    'due_date'          => $row['due_date'],
                    'value'             => $row['amount'],
                    'paid_at'           => null,
                ]);
            }

            return back()->with('success', 'Custo de Implantação configurado com sucesso.');
        });
    }

    /**
     * Atualiza apenas os metadados da Activation Fee (sem mexer nas parcelas).
     * Esta é a lógica movida do ClientController.
     */
    public function update(Request $request, Client $client): RedirectResponse
    {
        $fee = $client->activationFee;
        if (!$fee) {
            return back()->with('error', 'Este cliente não possui Custo de Implantação para atualizar.');
        }

        $data = $request->validate([
            'total_value' => ['required', 'numeric', 'min:0'],
            'notes'       => ['nullable', 'string', 'max:2000'],
        ]);

        $fee->update($data);

        return back()->with('success', 'Custo de Implantação atualizado com sucesso.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $fee = $client->activationFee;
        if (!$fee) {
            return back()->with('error', 'Este cliente não possui Custo de Implantação para excluir.');
        }

        return DB::transaction(function () use ($fee) {
            // Força a exclusão permanente das parcelas
            $fee->installments()->forceDelete();
            
            // Força a exclusão permanente do Custo "pai"
            $fee->forceDelete(); 

            return back()->with('success', 'Custo de Implantação excluído permanentemente.');
        });
    }


    // 
    // MÉTODOS PRIVADOS QUE ESTAVAM NO CLIENTCONTROLLER
    // 

    /**
     * Normaliza o payload de parcelas vindo do form, ou gera automaticamente.
     */
    private function normalizeInstallmentsPayload(array $payloadInstallments, int $count, float $total, string $firstDueDate): array
    {
        // se veio do form já editado, normaliza e completa o que faltar
        if (!empty($payloadInstallments)) {
            // ordena por installment_number, reindexa e garante tamanho $count
            $byNumber = collect($payloadInstallments)
                ->map(function ($i) { // cast básico
                    return [
                        'installment_number' => isset($i['installment_number']) ? (int)$i['installment_number'] : null,
                        'due_date'           => $i['due_date'] ?? null,
                        'amount'             => isset($i['amount']) ? (float)$i['amount'] : null,
                    ];
                })
                ->filter(fn ($i) => $i['installment_number']) // precisa ter número
                ->keyBy('installment_number');

            $result = [];
            for ($n = 1; $n <= $count; $n++) {
                $existing = $byNumber->get($n);

                $result[] = [
                    'installment_number' => $n,
                    'due_date'           => $existing['due_date'] ?? $this->addMonthsIso($firstDueDate, $n - 1),
                    'amount'             => isset($existing['amount']) ? (float)$existing['amount'] : $this->evenSplit($total, $count, $n),
                ];
            }
            return $result;
        }

        // caso contrário, gera automaticamente
        $rows = [];
        for ($n = 1; $n <= $count; $n++) {
            $rows[] = [
                'installment_number' => $n,
                'due_date'           => $this->addMonthsIso($firstDueDate, $n - 1),
                'amount'             => $this->evenSplit($total, $count, $n),
            ];
        }

        // ajuste de centavos na última parcela
        $sum = collect($rows)->sum('amount');
        if (round($sum, 2) !== round($total, 2)) {
            $diff = round($total - $sum, 2);
            $rows[$count - 1]['amount'] = round($rows[$count - 1]['amount'] + $diff, 2);
        }

        return $rows;
    }

    /**
     * Divide o total igualmente e retorna o valor de cada parcela.
     */
    private function evenSplit(float $total, int $count, int $n): float
    {
        return floor(($total / $count) * 100) / 100;
    }

    /**
     * Soma meses mantendo o dia, ajustando para o último dia do mês.
     */
    private function addMonthsIso(string $iso, int $months): string
    {
        [$y, $m, $d] = array_map('intval', explode('-', $iso));
        $base = mktime(0, 0, 0, $m + $months, 1, $y);
        $lastDay = (int) date('t', $base);
        $day = min($d ?: 1, $lastDay);
        return date('Y-m-d', mktime(0, 0, 0, date('n', $base), $day, date('Y', $base)));
    }

    public function renegotiate(Request $request, Client $client): RedirectResponse
    {
        $fee = $client->activationFee;

        if (!$fee) {
            return back()->with('error', 'Custo de Implantação não encontrado.');
        }

        // --- 1. Validação do Formulário ---
        $validated = $request->validate([
            'installments_count' => ['required', 'integer', 'min:1'],
            'first_due_date'     => ['required', 'date'],
        ], [
            'installments_count.min' => 'O número de parcelas deve ser pelo menos 1.',
        ]);

        return DB::transaction(function () use ($fee, $validated) {
            
            // --- 2. Calcular o Saldo Devedor Líquido ---
            $totalValue = (float) $fee->total_value;
            $totalPaid = (float) $fee->installments()->sum('paid_value');
            $netBalance = round($totalValue - $totalPaid, 2); // Saldo devedor líquido

            if ($netBalance <= 0) {
                return back()->with('error', 'Não há saldo devedor para renegociar.');
            }

            // --- 3. CORREÇÃO: Bloco de "forceDelete" REMOVIDO ---
            // Não vamos mais apagar o histórico.

            // --- 4. Descobrir o novo número da parcela ---
            // Pega o número mais alto de TODAS as parcelas
            $maxNumber = (int) $fee->installments()->max('installment_number');

            // --- 5. Gerar as novas parcelas ---
            $newInstallmentData = $this->normalizeInstallmentsPayload(
                [], // Sem payload existente
                (int) $validated['installments_count'],
                $netBalance, // O "Total" agora é o Saldo Devedor
                $validated['first_due_date']
            );

            // --- 6. Salvar as novas parcelas ---
            foreach ($newInstallmentData as $row) {
                FeeInstallment::create([
                    'activation_fee_id'  => $fee->id,
                    'installment_number' => $maxNumber + $row['installment_number'],
                    'value'              => $row['amount'],
                    'due_date'           => $row['due_date'],
                    'paid_value'         => null,
                    'paid_at'            => null,
                ]);
            }
            
            return back()->with('success', 'Novas parcelas de renegociação criadas com sucesso.');

        });
    }
}