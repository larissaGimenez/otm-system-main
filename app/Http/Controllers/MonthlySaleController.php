<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\MonthlySale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class MonthlySaleController extends Controller
{
    /**
     * Armazena um novo registro de faturamento (via modal).
     */
    public function store(Request $request, Contract $contract): RedirectResponse
    {
        $validatedData = $request->validate([
            'year'              => ['required', 'integer', 'min:2020', 'max:2099'],
            'month'             => [
                'required', 
                'integer', 
                'min:1', 
                'max:12',
                // Validação de duplicidade: não pode ter 2 registros pro mesmo PDV, Mês e Ano
                Rule::unique('monthly_sales')->where(function ($query) use ($contract) {
                    return $query->where('pdv_id', $contract->pdv_id)
                                 ->where('year', request('year'));
                })
            ],
            'gross_sales_value' => ['required', 'numeric', 'min:0'],
            'net_sales_value'   => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            // Adiciona os IDs do contrato e do PDV (para a regra 'unique' funcionar)
            $validatedData['contract_id'] = $contract->id;
            $validatedData['pdv_id'] = $contract->pdv_id;

            MonthlySale::create($validatedData);

            return back()->with('success', 'Faturamento registrado com sucesso.');

        } catch (\Throwable $e) {
            Log::error('Falha ao salvar faturamento mensal: ' . $e->getMessage());
            return back()->with('error', 'Erro ao salvar o faturamento.')->withInput();
        }
    }

    /**
     * Atualiza um faturamento (via modal).
     */
    public function update(Request $request, MonthlySale $monthlySale): RedirectResponse
    {
        $validatedData = $request->validate([
            'year'              => ['required', 'integer', 'min:2020', 'max:2099'],
            'month'             => [
                'required', 
                'integer', 
                'min:1', 
                'max:12',
                Rule::unique('monthly_sales')->where(function ($query) use ($monthlySale) {
                    return $query->where('pdv_id', $monthlySale->pdv_id)
                                 ->where('year', request('year'));
                })->ignore($monthlySale->id) // Ignora o próprio registro
            ],
            'gross_sales_value' => ['required', 'numeric', 'min:0'],
            'net_sales_value'   => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            $monthlySale->update($validatedData);
            return back()->with('success', 'Faturamento atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar faturamento mensal: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar o faturamento.')->withInput();
        }
    }

    /**
     * Remove um faturamento.
     */
    public function destroy(MonthlySale $monthlySale): RedirectResponse
    {
        try {
            $monthlySale->delete(); // Soft delete
            return back()->with('success', 'Registro de faturamento removido com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao remover faturamento: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover o registro.');
        }
    }
}