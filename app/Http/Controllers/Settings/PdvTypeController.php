<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\PdvType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PdvTypeController extends Controller
{
    public function index()
    {
        $types = PdvType::withCount('pdvs')->get();
        return view('settings.pdv.pdv-type', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        PdvType::create($validated);

        return back()->with('success', 'Tipo criado com sucesso!');
    }

    public function update(Request $request, PdvType $type) 
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $type->update($validated);

        return back()->with('success', 'Tipo atualizado com sucesso!');
    }

    public function destroy(PdvType $type)
    {
        if ($type->pdvs()->exists()) {
            return back()->with('error', 'Não é possível excluir este tipo pois existem PDVs vinculados a ele.');
        }

        $type->delete();

        return back()->with('success', 'Tipo removido com sucesso!');
    }
}