<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
{

    public function index(Request $request): View
    {
        $query = Equipment::query();

        if ($request->filled('search')) {
            $term = '%' . $request->input('search') . '%';

            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('type', 'like', $term)
                  ->orWhere('brand', 'like', $term)
                  ->orWhere('model', 'like', $term)
                  ->orWhere('serial_number', 'like', $term)
                  ->orWhere('asset_tag', 'like', $term)
                  ->orWhere('status', 'like', $term);
            });
        }

        $equipments = $query->withTrashed()->latest()->paginate(10);

        return view('equipments.index', compact('equipments'));
    }

    public function create(): View
    {
        return view('equipments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:50', 'unique:equipments,name'],
            'type'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:500'],
            'brand'         => ['nullable', 'string', 'max:255'],
            'model'         => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255', 'unique:equipments,serial_number'],
            'asset_tag'     => ['nullable', 'string', 'max:255', 'unique:equipments,asset_tag'],
            'photos'        => ['nullable', 'array'],
            'photos.*'      => ['image', 'max:2048'],
        ]);

        try {

            if ($request->hasFile('photos')) {
                $photos = [];
                foreach ($request->file('photos') as $photo) {
                    $photos[] = $photo->store('equipment_photos', 'public');
                }
                $validated['photos'] = $photos;
            }

            $validated['status'] = 'Disponível';

            Equipment::create($validated);

            return redirect()
                ->route('equipments.index')
                ->with('success', 'Equipamento cadastrado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao criar Equipamento: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()
                ->with('error', 'Ocorreu um erro ao cadastrar o Equipamento. Tente novamente.')
                ->withInput();
        }
    }

    public function show(Equipment $equipment): View
    {
        return view('equipments.show', compact('equipment'));
    }

    public function edit(Equipment $equipment): View
    {
        return view('equipments.edit', compact('equipment'));
    }

    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:50', Rule::unique('equipments', 'name')->ignore($equipment->id)],
            'type'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:500'],
            'brand'         => ['nullable', 'string', 'max:255'],
            'model'         => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255', Rule::unique('equipments', 'serial_number')->ignore($equipment->id)],
            'asset_tag'     => ['nullable', 'string', 'max:255', Rule::unique('equipments', 'asset_tag')->ignore($equipment->id)],
            'photos'        => ['nullable', 'array'],
            'photos.*'      => ['image', 'max:2048'],
        ]);

        try {
            if ($request->hasFile('photos')) {
                $existing = $equipment->photos ?? [];
                foreach ($request->file('photos') as $photo) {
                    $existing[] = $photo->store('equipment_photos', 'public');
                }
                $validated['photos'] = array_values(array_unique($existing));
            }

            unset($validated['status']);

            $equipment->update($validated);

            return redirect()
                ->route('equipments.index')
                ->with('success', 'Equipamento atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar Equipamento: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()
                ->with('error', 'Ocorreu um erro ao atualizar o Equipamento. Tente novamente.')
                ->withInput();
        }
    }

    public function destroy(Equipment $equipment): RedirectResponse
    {
        try {
            $equipment->delete();

            return redirect()
                ->route('equipments.index')
                ->with('success', 'Equipamento excluído com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao excluir Equipamento: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()
                ->with('error', 'Ocorreu um erro ao excluir o Equipamento. Tente novamente.');
        }
    }

    // Adicionar fotos
    public function addPhotos(\Illuminate\Http\Request $request, \App\Models\Equipment $equipment)
    {
        $request->validate([
            'photos'   => ['required','array','min:1'],
            'photos.*' => ['image','max:2048'],
        ]);

        try {
            $photos = $equipment->photos ?? [];
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('equipment_photos', 'public');
            }
            $equipment->update(['photos' => array_values(array_unique($photos))]);

            return back()->with('success', 'Foto(s) adicionada(s) com sucesso.');
        } catch (\Throwable $e) {
            \Log::error('Falha ao adicionar fotos: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return back()->with('error', 'Não foi possível adicionar as fotos. Tente novamente.');
        }
    }

    // Remover foto por índice
    public function removePhoto(\App\Models\Equipment $equipment, int $index)
    {
        try {
            $photos = $equipment->photos ?? [];
            if (!isset($photos[$index])) {
                return back()->with('error', 'Foto não encontrada.');
            }

            $path = $photos[$index];
            if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }

            unset($photos[$index]);
            $equipment->update(['photos' => array_values($photos)]);

            return back()->with('success', 'Foto removida com sucesso.');
        } catch (\Throwable $e) {
            \Log::error('Falha ao remover foto: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return back()->with('error', 'Não foi possível remover a foto. Tente novamente.');
        }
    }

}
