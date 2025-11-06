<?php

namespace App\Http\Controllers;

use App\Models\FeeInstallment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeeInstallmentController extends Controller
{
   
    
    public function pay(Request $request, FeeInstallment $feeInstallment): RedirectResponse
    {
        $validated = $request->validate([
            'paid_at'    => ['required', 'date'],
            'paid_value' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            // Se for um pagamento parcial, o valor pago é somado ao anterior
            // (Esta é uma sugestão de lógica, você pode optar por apenas substituir)
            $existingPaid = (float) $feeInstallment->paid_value ?? 0.0;
            $newValue = (float) $validated['paid_value'];

            // Vamos usar a lógica de "substituir" o valor pago, é mais simples
            // Se quiser somar, mude 'paid_value' => $newValue
            // para 'paid_value' => $existingPaid + $newValue
            
            $feeInstallment->update([
                'paid_at'    => $validated['paid_at'],
                'paid_value' => $newValue,
                // 'is_paid' => true, // <-- REMOVIDO
            ]);

            return back()->with('success', 'Pagamento da parcela registrado.');

        } catch (\Throwable $e) {
            Log::error('Falha ao pagar parcela: ' . $e->getMessage());
            return back()->with('error', 'Erro ao registrar o pagamento.');
        }
    }

    /**
     * Estorna um pagamento (zera os campos de pagamento).
     */
    public function unpay(FeeInstallment $feeInstallment): RedirectResponse
    {
        try {
            $feeInstallment->update([
                'paid_at'    => null,
                'paid_value' => null,
                // 'is_paid' => false, // <-- REMOVIDO
            ]);

            return back()->with('success', 'Pagamento da parcela estornado.');

        } catch (\Throwable $e) {
            Log::error('Falha ao estornar parcela: ' . $e->getMessage());
            return back()->with('error', 'Erro ao estornar pagamento.');
        }
    }
}