<?php

namespace App\Http\Controllers;

use App\Models\Pdv;
use App\Models\PdvStatus;
use App\Models\PdvType;
use App\Models\Equipment;
use App\Models\ExternalId;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PdvController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pdv::query()->with(['client', 'status', 'type']);

        if ($request->filled('status')) {
            $query->whereHas('status', function($q) use ($request) {
                $q->where('slug', $request->input('status'));
            });
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('street', 'like', $searchTerm)
                  ->orWhereHas('type', fn($q) => $q->where('name', 'like', $searchTerm))
                  ->orWhereHas('status', fn($q) => $q->where('name', 'like', $searchTerm))
                  ->orWhereHas('client', fn($q) => $q->where('name', 'like', $searchTerm));
            });
        }

        $pdvs = $query->latest()->paginate(10)->withQueryString();
        
        // CORREÇÃO: Busca todos os status para as abas da index
        $allStatuses = PdvStatus::all();

        return view('pdvs.index', compact('pdvs', 'allStatuses'));
    }

    public function create(): View
    {
        // CORREÇÃO: Busca do banco para preencher os selects
        return view('pdvs.create', [
            'statuses' => PdvStatus::all(),
            'types'    => PdvType::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name'          => ['required', 'string', 'max:255', Rule::unique('pdvs', 'name')],
            'description'   => ['nullable', 'string'],
            'pdv_type_id'   => ['required', 'exists:pdv_types,id'],
            'pdv_status_id' => ['required', 'exists:pdv_statuses,id'],
            'street'        => ['nullable', 'string', 'max:255'],
            'number'        => ['nullable', 'string', 'max:50'],
            'complement'    => ['nullable', 'string', 'max:255'],
            'photos'        => ['nullable', 'array'],
            'photos.*'      => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'videos'        => ['nullable', 'array'],
            'videos.*'      => ['mimetypes:video/mp4,video/avi,video/mpeg', 'max:20480'],
        ]);

        try {
            if ($request->hasFile('photos')) {
                $photoPaths = [];
                foreach ($request->file('photos') as $photo) {
                    $photoPaths[] = $photo->store('pdv_photos', 'public');
                }
                $validatedData['photos'] = $photoPaths;
            }

            if ($request->hasFile('videos')) {
                $videoPaths = [];
                foreach ($request->file('videos') as $video) {
                    $videoPaths[] = $video->store('pdv_videos', 'public');
                }
                $validatedData['videos'] = $videoPaths;
            }

            $validatedData['slug'] = Str::slug($validatedData['name']);
            
            Pdv::create($validatedData);

            return redirect()
                ->route('pdvs.index')
                ->with('success', 'Ponto de Venda cadastrado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao criar PDV: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            return back()
                ->with('error', 'Ocorreu um erro ao cadastrar. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Pdv $pdv)
    {
        $pdv->load(['equipments', 'status', 'type', 'client']);

        $externalIdRecords = ExternalId::forItem($pdv->id)->latest()->get();
        $availableEquipments = Equipment::whereDoesntHave('pdvs')->get();

        return view('pdvs.show', [
            'pdv'                 => $pdv,
            'availableEquipments' => $availableEquipments,
            'externalIdRecords'   => $externalIdRecords,
        ]);
    }

    public function edit(Pdv $pdv): View
    {
        // CORREÇÃO: Busca do banco para preencher os selects
        return view('pdvs.edit', [
            'pdv'      => $pdv,
            'statuses' => PdvStatus::all(),
            'types'    => PdvType::all(),
        ]);
    }

    public function update(Request $request, Pdv $pdv): RedirectResponse
    {
        $validatedData = $request->validate([
            'name'          => ['required', 'string', 'max:255', Rule::unique('pdvs', 'name')->ignore($pdv->id)],
            'description'   => ['nullable', 'string'],
            'pdv_type_id'   => ['required', 'exists:pdv_types,id'],
            'pdv_status_id' => ['required', 'exists:pdv_statuses,id'],
            'street'        => ['nullable', 'string', 'max:255'],
            'number'        => ['nullable', 'string', 'max:50'],
            'complement'    => ['nullable', 'string', 'max:255'],
            'photos'        => ['nullable', 'array'],
            'photos.*'      => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'videos'        => ['nullable', 'array'],
            'videos.*'      => ['mimetypes:video/mp4,video/avi,video/mpeg', 'max:20480'],
        ]);

        try {
            if ($request->hasFile('photos')) {
                $photoPaths = $pdv->photos ?? [];
                foreach ($request->file('photos') as $photo) {
                    $photoPaths[] = $photo->store('pdv_photos', 'public');
                }
                $validatedData['photos'] = array_values(array_unique($photoPaths));
            }

            if ($request->hasFile('videos')) {
                $videoPaths = $pdv->videos ?? [];
                foreach ($request->file('videos') as $video) {
                    $videoPaths[] = $video->store('pdv_videos', 'public');
                }
                $validatedData['videos'] = array_values(array_unique($videoPaths));
            }

            $pdv->update($validatedData);

            return redirect()
                ->route('pdvs.index')
                ->with('success', 'Ponto de Venda atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar PDV: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()
                ->with('error', 'Ocorreu um erro ao atualizar o Ponto de Venda.')
                ->withInput();
        }
    }

    public function destroy(Pdv $pdv): RedirectResponse
    {
        try {
            $pdv->update([
                'pdv_status_id' => null,
                'pdv_type_id'   => null,
            ]);

            $pdv->delete();

            return redirect()
                ->route('pdvs.index')
                ->with('success', 'Ponto de Venda excluído com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao excluir PDV: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()
                ->with('error', 'Ocorreu um erro ao excluir o Ponto de Venda.');
        }
    }

    public function attachEquipment(Request $request, Pdv $pdv): RedirectResponse
    {
        $data = $request->validate([
            'equipments'   => ['required', 'array', 'min:1'],
            'equipments.*' => ['uuid', 'exists:equipments,id'],
        ]);

        return DB::transaction(function () use ($pdv, $data) {
            $toAttach = Equipment::available()
                ->whereIn('id', $data['equipments'])
                ->pluck('id')
                ->all();

            if (empty($toAttach)) {
                return back()->with('error', 'Nenhum equipamento disponível para associar.');
            }

            $pdv->equipments()->syncWithoutDetaching($toAttach);

            // Nota: Se você também converteu EquipmentStatus para tabela, 
            // aqui precisará buscar o ID pelo slug, igual fizemos no destroy acima.
            // Se EquipmentStatus ainda é Enum, mantenha como está.
            Equipment::whereIn('id', $toAttach)->update([
                'status' => EquipmentStatus::IN_USE 
            ]);

            $ignored = array_diff($data['equipments'], $toAttach);

            if (!empty($ignored)) {
                return back()->with('success', 'Equipamento(s) disponível(is) associado(s). Alguns já estavam em uso e foram ignorados.');
            }

            return back()->with('success', 'Equipamento(s) associado(s) com sucesso.');
        });
    }

    public function detachEquipment(Pdv $pdv, Equipment $equipment): RedirectResponse
    {
        return DB::transaction(function () use ($pdv, $equipment) {
            $pdv->equipments()->detach($equipment->id);

            if (!$equipment->pdvs()->exists()) {
                $equipment->update([
                    'status' => EquipmentStatus::AVAILABLE
                ]);
            }

            return back()->with('success', 'Equipamento desassociado com sucesso.');
        });
    }

    public function addMedia(Request $request, Pdv $pdv): RedirectResponse
    {
        $validatedData = $request->validate([
            'photos'   => ['nullable', 'array'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'videos'   => ['nullable', 'array'],
            'videos.*' => ['mimetypes:video/mp4,video/avi,video/mpeg', 'max:20480'],
        ]);

        try {
            $photoPaths = $pdv->photos ?? [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPaths[] = $photo->store('pdv_photos', 'public');
                }
            }

            $videoPaths = $pdv->videos ?? [];
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $videoPaths[] = $video->store('pdv_videos', 'public');
                }
            }

            $pdv->update([
                'photos' => $photoPaths,
                'videos' => $videoPaths,
            ]);

            return back()->with('success', 'Mídia adicionada com sucesso!');

        } catch (\Throwable $e) {
            Log::error('Falha ao adicionar mídia ao PDV: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao enviar a mídia. Tente novamente.');
        }
    }

    public function destroyMedia(Pdv $pdv, string $type, int $index): RedirectResponse
    {
        try {
            $mediaArray = $pdv->$type ?? [];

            if (isset($mediaArray[$index])) {
                $filePathToDelete = $mediaArray[$index];

                unset($mediaArray[$index]);

                Storage::disk('public')->delete($filePathToDelete);

                $pdv->update([$type => array_values($mediaArray)]);

                return back()->with('success', 'Mídia removida com sucesso.');
            }

            return back()->with('error', 'Mídia não encontrada para remoção.');

        } catch (\Throwable $e) {
            Log::error('Falha ao remover mídia do PDV: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao remover a mídia. Tente novamente.');
        }
    }
}