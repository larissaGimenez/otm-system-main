<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\EquipmentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EquipmentStatusController extends Controller
{
    public function index()
    {
        $statuses = EquipmentStatus::withCount('equipments')->orderBy('name')->get();
        return view('settings.equipments.equipment-status', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:20'],
        ]);

        EquipmentStatus::create([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'color' => $request->color,
        ]);

        return back()->with('success', 'Status criado com sucesso.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:20'],
        ]);

        $status = EquipmentStatus::findOrFail($id);

        $status->update([
            'name'  => $request->name,
            'slug'  => Str::slug($request->name),
            'color' => $request->color,
        ]);

        return back()->with('success', 'Status atualizado com sucesso.');
    }

    public function destroy(string $id)
    {
        $status = EquipmentStatus::findOrFail($id);

        if ($status->equipments()->count() > 0) {
            return back()->with('error', 'Este status está em uso e não pode ser excluído.');
        }

        $status->delete();
        return back()->with('success', 'Status removido com sucesso.');
    }
}
