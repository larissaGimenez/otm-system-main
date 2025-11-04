<?php

namespace App\Http\Controllers;

use App\Models\FeeInstallment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FeeInstallmentController extends Controller
{
    /**
     * Marca uma parcela como PAGA.
     */
    public function pay(FeeInstallment $feeInstallment): RedirectResponse
    {
        try {
            $feeInstallment->update(['paid_at' => Carbon::now()]);
            return back()->with('success', 'Parcela marcada como paga.');
        } catch (\Throwable $e) {
            Log::error('Falha ao pagar parcela: ' . $e->getMessage());
            return back()->with('error', 'Erro ao marcar parcela como paga.');
        }
    }

    /**
     * Marca uma parcela como NÃO PAGA (estorno).
     */
    public function unpay(FeeInstallment $feeInstallment): RedirectResponse
    {
        try {
            $feeInstallment->update(['paid_at' => null]);
            return back()->with('success', 'Pagamento da parcela estornado.');
        } catch (\Throwable $e) {
            Log::error('Falha ao estornar parcela: ' . $e->getMessage());
            return back()->with('error', 'Erro ao estornar pagamento.');
        }
    }
}