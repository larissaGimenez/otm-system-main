<?php

namespace App\Http\Controllers;

use App\Enums\Pdv\FeePaymentMethod;
use App\Models\ActivationFee;
use App\Models\Pdv;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ActivationFeeController extends Controller
{
    /**
     * Armazena o Custo de Implantação e GERA as parcelas.
     */
    public function store(Request $request, Pdv $pdv): RedirectResponse
    {
        // Validação dos dados da "capa" e das regras de parcelamento
        $validatedData = $request->validate([
            'payment_method'      => ['required', Rule::in(array_column(FeePaymentMethod::cases(), 'value'))],
            'due_date'            => ['nullable', 'date'],
            'notes'               => ['nullable', 'string'],
            'total_value'         => ['required', 'numeric', 'min:0.01'],
            'installments_count'  => ['required', 'integer', 'min:1', 'max:120'],
            'first_due_date'      => ['required', 'date'],
        ]);

        // Inicia uma transação de banco de dados
        return DB::transaction(function () use ($request, $pdv, $validatedData) {
            try {
                // 1. Cria a "Capa" (ActivationFee)
                $activationFee = $pdv->activationFee()->create([
                    'payment_method'     => $validatedData['payment_method'],
                    'installments_count' => $validatedData['installments_count'],
                    'due_date'           => $validatedData['due_date'],
                    'notes'              => $validatedData['notes'],
                ]);

                // 2. Calcula o valor de cada parcela
                $totalValue = (float) $validatedData['total_value'];
                $count = (int) $validatedData['installments_count'];
                $firstDueDate = Carbon::parse($validatedData['first_due_date']);
                
                // Distribui o valor, cuidando da "diferença de centavos"
                $baseValue = floor(($totalValue / $count) * 100) / 100;
                $remainder = $totalValue - ($baseValue * $count);
                
                $installments = [];
                for ($i = 1; $i <= $count; $i++) {
                    $installmentValue = $baseValue;
                    // Adiciona a diferença na primeira parcela
                    if ($i === 1) {
                        $installmentValue += $remainder;
                    }

                    $installments[] = [
                        'installment_number' => $i,
                        'value'              => $installmentValue,
                        'due_date'           => $firstDueDate->clone()->addMonths($i - 1),
                    ];
                }

                // 3. Salva todas as parcelas no banco
                $activationFee->installments()->createMany($installments);

                return back()->with('success', 'Custo de implantação e parcelas gerados com sucesso.');

            } catch (\Throwable $e) {
                Log::error('Falha ao salvar Custo de Implantação: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
                // Desfaz a transação em caso de erro
                DB::rollBack();
                return back()->with('error', 'Erro ao salvar o custo: ' . $e->getMessage())->withInput();
            }
        });
    }

    /**
     * Atualiza as notas ou data de vencimento geral (via modal).
     */
    public function update(Request $request, ActivationFee $activationFee): RedirectResponse
    {
        $validatedData = $request->validate([
            'payment_method' => ['required', Rule::in(array_column(FeePaymentMethod::cases(), 'value'))],
            'due_date'       => ['nullable', 'date'],
            'notes'          => ['nullable', 'string'],
        ]);

        try {
            $activationFee->update($validatedData);
            return back()->with('success', 'Custo atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar custo: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar o custo.')->withInput();
        }
    }

    /**
     * Remove o Custo de Implantação e TODAS as suas parcelas.
     */
    public function destroy(ActivationFee $activationFee): RedirectResponse
    {
        try {
            // $this->authorize('delete', $activationFee);
            $activationFee->delete(); // Soft delete (vai dar cascade)
            return back()->with('success', 'Custo de implantação removido com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao remover custo: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover o custo.');
        }
    }
}