<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\EquipmentType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EquipmentTypeController extends Controller
{
    public function index()
    {
        $types = EquipmentType::withCount('equipments')->orderBy('name')->get();
        return view('settings.equipments.equipment-type', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        EquipmentType::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Tipo criado com sucesso.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $type = EquipmentType::findOrFail($id);

        $type->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return back()->with('success', 'Tipo atualizado com sucesso.');
    }

    public function destroy(string $id)
    {
        $type = EquipmentType::findOrFail($id);

        if ($type->equipments()->count() > 0) {
            return back()->with('error', 'Este tipo está em uso e não pode ser excluído.');
        }

        $type->delete();
        return back()->with('success', 'Tipo removido com sucesso.');
    }
}
