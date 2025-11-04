<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Enums\Equipment\EquipmentStatus;
use App\Enums\Equipment\EquipmentType;

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

    /**
     * CORRIGIDO: Passa os Enums para a view
     */
    public function create(): View
    {
        return view('equipments.create', [
            'types'    => EquipmentType::cases(),
            'statuses' => EquipmentStatus::cases(),
        ]);
    }

    /**
     * CORRIGIDO: Validação de 'type' e valor de 'status'
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:50', 'unique:equipments,name'],
            'type'          => ['required', Rule::in(array_column(EquipmentType::cases(), 'value'))], 
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

            $validated['slug'] = Str::slug($validated['name']);
            $validated['status'] = EquipmentStatus::AVAILABLE->value;

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

    /**
     * CORRIGIDO: Passa os Enums para a view
     */
    public function edit(Equipment $equipment): View
    {
        return view('equipments.edit', [
            'equipment' => $equipment,
            'types'     => EquipmentType::cases(),
            'statuses'  => EquipmentStatus::cases(),
        ]);
    }

    /**
     * CORRIGIDO: Validação de 'type' e 'status'
     */
    public function update(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:50', Rule::unique('equipments', 'name')->ignore($equipment->id)],
            // Validação corrigida
            'type'          => ['required', Rule::in(array_column(EquipmentType::cases(), 'value'))], 
            'description'   => ['nullable', 'string', 'max:500'],
            'brand'         => ['nullable', 'string', 'max:255'],
            'model'         => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255', Rule::unique('equipments', 'serial_number')->ignore($equipment->id)],
            'asset_tag' => ['nullable', 'string', 'max:255', Rule::unique('equipments', 'asset_tag')->ignore($equipment->id)],
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

            // O status não é atualizado aqui, é gerenciado pelo PdvController
            // (Isso está correto, mantive sua lógica)
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
        // ... (sem alterações aqui) ...
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

    public function storeMedia(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'photos'   => ['nullable', 'array'],
            'photos.*' => ['image', 'max:2048'], // Apenas imagens
            'videos'   => ['nullable', 'array'],
            'videos.*' => ['mimetypes:video/mp4,video/quicktime', 'max:50000'], // Apenas vídeos
        ]);

        try {
            $photos = $equipment->photos ?? [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photos[] = $photo->store('equipment_photos', 'public');
                }
            }

            $videos = $equipment->videos ?? [];
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $videos[] = $video->store('equipment_videos', 'public');
                }
            }

            $equipment->update([
                'photos' => array_values(array_unique($photos)),
                'videos' => array_values(array_unique($videos)),
            ]);

            return back()->with('success', 'Mídia(s) adicionada(s) com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao adicionar mídia ao Equipamento: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return back()->with('error', 'Não foi possível adicionar as mídias. Tente novamente.');
        }
    }

    /**
     * NOVO MÉTODO (Substitui o removeMedia e corrige a assinatura da rota)
     * Remove uma mídia (foto ou vídeo) de um equipamento.
     */
    public function destroyMedia(Equipment $equipment, string $type, int $index): RedirectResponse
    {
        // Valida o 'type' para garantir que é 'photos' ou 'videos'
        if (!in_array($type, ['photos', 'videos'])) {
            return back()->with('error', 'Tipo de mídia inválido.');
        }

        try {
            // Usa a variável $type para acessar a propriedade correta
            $media = $equipment->$type ?? []; // $equipment->photos ou $equipment->videos

            if (!isset($media[$index])) {
                return back()->with('error', 'Mídia não encontrada.');
            }

            // Remove o arquivo do disco
            $path = $media[$index];
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Remove do array e reindexa
            unset($media[$index]);
            $equipment->update([$type => array_values($media)]); // Salva o array atualizado

            return back()->with('success', 'Mídia removida com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao remover mídia do Equipamento: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
            return back()->with('error', 'Não foi possível remover a mídia. Tente novamente.');
        }
    }
}