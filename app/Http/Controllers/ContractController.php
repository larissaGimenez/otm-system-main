<?php

namespace App\Http\Controllers;

use App\Models\Client; // <-- Importe o Client
use App\Models\Contract;
// use App\Models\Pdv; // <-- Removido
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Importe o Storage
use Illuminate\Validation\Rule;

class ContractController extends Controller
{
    /**
     * Armazena um novo contrato.
     * CORRIGIDO: Recebe (Request $request, Client $client)
     */
    public function store(Request $request, Client $client): RedirectResponse
    {
        $validatedData = $request->validate([
            'signed_at'           => ['required', 'date'],
            'has_monthly_fee'     => ['nullable', 'boolean'],
            'monthly_fee_value'   => ['nullable', 'required_if:has_monthly_fee,true', 'numeric', 'min:0'],
            'monthly_fee_due_day' => ['nullable', 'required_if:has_monthly_fee,true', 'integer', 'min:1', 'max:31'],
            'has_commission'      => ['nullable', 'boolean'],
            'commission_percentage' => ['nullable', 'required_if:has_commission,true', 'numeric', 'min:0', 'max:100'],
            'pdf_file'            => ['nullable', 'file', 'mimes:pdf', 'max:5120'], 
        ]);

        try {
            // CORREÇÃO: Adiciona o client_id
            $validatedData['client_id'] = $client->id;
            $validatedData['has_monthly_fee'] = $request->boolean('has_monthly_fee');
            $validatedData['has_commission'] = $request->boolean('has_commission');

            // Lógica de Upload de PDF
            if ($request->hasFile('pdf_file')) {
                $validatedData['pdf_path'] = $request->file('pdf_file')->store('contract_pdfs', 'public');
            }

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
        // ... (Seu método update que eu enviei antes)
        $validatedData = $request->validate([
            'signed_at'           => ['required', 'date'],
            'has_monthly_fee'     => ['nullable', 'boolean'],
            'monthly_fee_value'   => ['nullable', 'required_if:has_monthly_fee,true', 'numeric', 'min:0'],
            'monthly_fee_due_day' => ['nullable', 'required_if:has_monthly_fee,true', 'integer', 'min:1', 'max:31'],
            'has_commission'      => ['nullable', 'boolean'],
            'commission_percentage' => ['nullable', 'required_if:has_commission,true', 'numeric', 'min:0', 'max:100'],
            'pdf_file'            => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        try {
            $validatedData['has_monthly_fee'] = $request->boolean('has_monthly_fee');
            $validatedData['has_commission'] = $request->boolean('has_commission');

            if ($request->hasFile('pdf_file')) {
                if ($contract->pdf_path) {
                    Storage::disk('public')->delete($contract->pdf_path);
                }
                $validatedData['pdf_path'] = $request->file('pdf_file')->store('contract_pdfs', 'public');
            }

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
        // ... (Seu método destroy que eu enviei antes)
        try {
            if ($contract->pdf_path) {
                Storage::disk('public')->delete($contract->pdf_path);
            }
            $contract->delete(); // Soft delete
            return back()->with('success', 'Contrato removido com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao remover contrato: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover o contrato.');
        }
    }
}