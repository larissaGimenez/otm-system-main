<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\EquipmentStatus;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Equipment::query();

        if ($request->filled('search')) {
            $term = '%' . $request->input('search') . '%';

            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('brand', 'like', $term)
                  ->orWhere('model', 'like', $term)
                  ->orWhere('serial_number', 'like', $term)
                  ->orWhere('asset_tag', 'like', $term)
                  ->orWhereHas('type', fn($t) => $t->where('name', 'like', $term))
                  ->orWhereHas('status', fn($s) => $s->where('name', 'like', $term));
            });
        }

        $equipments = $query->with(['type', 'status'])->withTrashed()->latest()->paginate(10);

        return view('equipments.index', compact('equipments'));
    }

    public function create(): View
    {
        return view('equipments.create', [
            'types'    => EquipmentType::orderBy('name')->get(),
            'statuses' => EquipmentStatus::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:50', 'unique:equipments,name'],
            'equipment_type_id'  => ['required', 'exists:equipment_types,id'],
            'equipment_status_id'=> ['required', 'exists:equipment_statuses,id'],
            'description'        => ['nullable', 'string', 'max:500'],
            'brand'              => ['nullable', 'string', 'max:255'],
            'model'              => ['nullable', 'string', 'max:255'],
            'serial_number'      => ['nullable', 'string', 'max:255', 'unique:equipments,serial_number'],
            'asset_tag'          => ['nullable', 'string', 'max:255', 'unique:equipments,asset_tag'],
            'photos'             => ['nullable', 'array'],
            'photos.*'           => ['image', 'max:2048'],
        ]);

        try {
            if ($request->hasFile('photos')) {
                $photos = [];
                foreach ($request->file('photos') as $photo) {
                    $photos[] = $photo->store('equipment_photos', 'public');
                }
                $validated['photos'] = $photos;
            }

            $validated['slug'] = Str::slug($validated['name']);

            Equipment::create($validated);

            return redirect()
                ->route('equipments.index')
                ->with('success', 'Equipamento cadastrado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao criar equipamento: ' . $e->getMessage());
            return back()->with('error', 'Erro ao cadastrar.')->withInput();
        }
    }

    public function show(Equipment $equipment): View
    {
        $equipment->load(['type', 'status']);
        return view('equipments.show', compact('equipment'));
    }

    public function edit(Equipment $equipment): View
    {
        return view('equipments.edit', [
            'equipment' => $equipment,
            'types'     => EquipmentType::orderBy('name')->get(),
            'statuses'  => EquipmentStatus::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:50', Rule::unique('equipments', 'name')->ignore($equipment->id)],
            'equipment_type_id'  => ['required', 'exists:equipment_types,id'],
            'equipment_status_id'=> ['required', 'exists:equipment_statuses,id'],
            'description'        => ['nullable', 'string', 'max:500'],
            'brand'              => ['nullable', 'string', 'max:255'],
            'model'              => ['nullable', 'string', 'max:255'],
            'serial_number'      => ['nullable', 'string', 'max:255', Rule::unique('equipments', 'serial_number')->ignore($equipment->id)],
            'asset_tag'          => ['nullable', 'string', 'max:255', Rule::unique('equipments', 'asset_tag')->ignore($equipment->id)],
            'photos'             => ['nullable', 'array'],
            'photos.*'           => ['image', 'max:2048'],
        ]);

        try {
            if ($request->hasFile('photos')) {
                $existing = $equipment->photos ?? [];
                foreach ($request->file('photos') as $photo) {
                    $existing[] = $photo->store('equipment_photos', 'public');
                }
                $validated['photos'] = array_values(array_unique($existing));
            }

            $equipment->update($validated);

            return redirect()
                ->route('equipments.index')
                ->with('success', 'Equipamento atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar Equipment: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar.')->withInput();
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
            Log::error('Falha ao excluir equipamento: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir.');
        }
    }

    public function storeMedia(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'photos'   => ['nullable', 'array'],
            'photos.*' => ['image', 'max:4096'], // até 4MB
            'videos'   => ['nullable', 'array'],
            'videos.*' => ['mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/mpeg', 'max:204800'], // até 200MB
        ]);

        try {
            $photos = $equipment->photos ?? [];
            $videos = $equipment->videos ?? [];

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photos[] = $photo->store('equipment_photos', 'public');
                }
            }

            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $videos[] = $video->store('equipment_videos', 'public');
                }
            }

            $equipment->update([
                'photos' => $photos,
                'videos' => $videos,
            ]);

            return back()->with('success', 'Mídia adicionada com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao adicionar mídia ao equipamento: '.$e->getMessage());
            return back()->with('error', 'Erro ao enviar mídia.');
        }
    }


    public function destroyMedia(Equipment $equipment, string $type, int $index): RedirectResponse
    {
        if (!in_array($type, ['photos', 'videos'])) {
            return back()->with('error', 'Tipo inválido.');
        }

        try {
            $media = $equipment->$type ?? [];

            if (!isset($media[$index])) {
                return back()->with('error', 'Arquivo não encontrado.');
            }

            $path = $media[$index];

            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            unset($media[$index]);

            $equipment->update([
                $type => array_values($media)
            ]);

            return back()->with('success', 'Mídia removida com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao remover mídia: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover mídia.');
        }
    }
}
