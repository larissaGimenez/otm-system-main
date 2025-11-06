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
                // CORREÇÃO: A regra unique agora usa 'contract_id'
                Rule::unique('monthly_sales')->where(function ($query) use ($contract) {
                    return $query->where('contract_id', $contract->id)
                                 ->where('year', request('year'));
                })
            ],
            'gross_sales_value' => ['required', 'numeric', 'min:0'],
            'net_sales_value'   => ['nullable', 'numeric', 'min:0'],
        ]);

        try {
            $validatedData['contract_id'] = $contract->id;
            // $validatedData['pdv_id'] = $contract->pdv_id; // <-- REMOVIDO (não existe mais)

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
                // CORREÇÃO: A regra unique agora usa 'contract_id'
                Rule::unique('monthly_sales')->where(function ($query) use ($monthlySale) {
                    return $query->where('contract_id', $monthlySale->contract_id)
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