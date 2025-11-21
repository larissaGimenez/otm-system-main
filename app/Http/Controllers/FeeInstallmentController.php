<?php

namespace App\Http\Controllers;

use App\Models\FeeInstallment;
use Illuminate\Http\Request;

class FeeInstallmentController extends Controller
{
    public function update(Request $request, FeeInstallment $feeInstallment)
    {
        $validated = $request->validate([
            'due_date' => 'required|date',
        ]);

        $feeInstallment->update([
            'due_date' => $validated['due_date']
        ]);

        return back()->with('success', 'Data de vencimento atualizada com sucesso.');
    }

    public function pay(Request $request, FeeInstallment $feeInstallment)
    {
        $validated = $request->validate([
            'paid_at' => 'required|date',
        ]);

        $feeInstallment->update([
            'paid_at'    => $validated['paid_at'],
            'paid_value' => $feeInstallment->value, 
        ]);

        return back()->with('success', 'Pagamento registrado com sucesso.');
    }
}