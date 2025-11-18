<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\PdvStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PdvStatusController extends Controller
{
    public function index()
    {
        $statuses = PdvStatus::withCount('pdvs')->get(); 
        return view('settings.pdv.pdv-status', compact('statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:50',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        PdvStatus::create($validated);

        return back()->with('success', 'Status criado com sucesso!');
    }

    public function update(Request $request, PdvStatus $pdvStatus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:50',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $pdvStatus->update($validated);

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    public function destroy(PdvStatus $pdvStatus)
    {
        if ($pdvStatus->pdvs()->exists()) {
            return back()->with('error', 'Não é possível excluir este status pois existem PDVs vinculados a ele.');
        }

        $pdvStatus->delete();

        return back()->with('success', 'Status removido com sucesso!');
    }
}