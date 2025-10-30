<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Pdv;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ContractController extends Controller
{
    /**
     * Armazena um novo contrato (via modal).
     */
    public function store(Request $request, Pdv $pdv): RedirectResponse
    {
        $validatedData = $request->validate([
            'signed_at'             => ['required', 'date'],
            'has_monthly_fee'       => ['nullable', 'boolean'],
            'monthly_fee_value'     => ['nullable', 'required_if:has_monthly_fee,true', 'numeric', 'min:0'],
            'monthly_fee_due_day'   => ['nullable', 'required_if:has_monthly_fee,true', 'integer', 'min:1', 'max:31'],
            'has_commission'        => ['nullable', 'boolean'],
            'commission_percentage' => ['nullable', 'required_if:has_commission,true', 'numeric', 'min:0', 'max:100'],
            'payment_bank_name'     => ['nullable', 'string', 'max:255'],
            'payment_bank_agency'   => ['nullable', 'string', 'max:255'],
            'payment_bank_account'  => ['nullable', 'string', 'max:255'],
            'payment_pix_key'       => ['nullable', 'string', 'max:255'],
        ]);

        try {
            // Adiciona o PDV ID e ajusta os booleanos (checkboxes podem não enviar 'false')
            $validatedData['pdv_id'] = $pdv->id;
            $validatedData['has_monthly_fee'] = $request->boolean('has_monthly_fee');
            $validatedData['has_commission'] = $request->boolean('has_commission');

            Contract::create($validatedData);

            return back()->with('success', 'Contrato adicionado com sucesso.');

        } catch (\Throwable $e) {
            Log::error('Falha ao salvar contrato: ' . $e->getMessage());
            return back()->with('error', 'Erro ao salvar o contrato.')->withInput();
        }
    }

    /**
     * Atualiza um contrato (via modal).
     */
    public function update(Request $request, Contract $contract): RedirectResponse
    {
        $validatedData = $request->validate([
            'signed_at'             => ['required', 'date'],
            'has_monthly_fee'       => ['nullable', 'boolean'],
            'monthly_fee_value'     => ['nullable', 'required_if:has_monthly_fee,true', 'numeric', 'min:0'],
            'monthly_fee_due_day'   => ['nullable', 'required_if:has_monthly_fee,true', 'integer', 'min:1', 'max:31'],
            'has_commission'        => ['nullable', 'boolean'],
            'commission_percentage' => ['nullable', 'required_if:has_commission,true', 'numeric', 'min:0', 'max:100'],
            'payment_bank_name'     => ['nullable', 'string', 'max:255'],
            'payment_bank_agency'   => ['nullable', 'string', 'max:255'],
            'payment_bank_account'  => ['nullable', 'string', 'max:255'],
            'payment_pix_key'       => ['nullable', 'string', 'max:255'],
        ]);

        try {
            // Ajusta os booleanos (checkboxes podem não enviar 'false')
            $validatedData['has_monthly_fee'] = $request->boolean('has_monthly_fee');
            $validatedData['has_commission'] = $request->boolean('has_commission');

            $contract->update($validatedData);

            return back()->with('success', 'Contrato atualizado com sucesso.');

        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar contrato: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar o contrato.')->withInput();
        }
    }

    /**
     * Remove um contrato.
     */
    public function destroy(Contract $contract): RedirectResponse
    {
        try {
            // Adicionar verificação de policy se necessário
            // $this->authorize('delete', $contract);

            $contract->delete(); // Soft delete

            return back()->with('success', 'Contrato removido com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao remover contrato: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover o contrato.');
        }
    }
}