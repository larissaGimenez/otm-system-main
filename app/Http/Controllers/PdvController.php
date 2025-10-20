<?php

namespace App\Http\Controllers;

use App\Models\Pdv;
use App\Models\Equipment;
use App\Models\ExternalId;

use App\Enums\Pdv\PdvStatus;
use App\Enums\Pdv\PdvType;

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
        $query = Pdv::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('type', 'like', $searchTerm)
                  ->orWhere('status', 'like', $searchTerm)
                  ->orWhere('street', 'like', $searchTerm);
            });
        }

        $pdvs = $query->withTrashed()->latest()->paginate(10);

        return view('pdvs.index', compact('pdvs'));
    }

    public function create(): View
    {
        return view('pdvs.create', [
            'statuses' => PdvStatus::cases(),
            'types'    => PdvType::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('pdvs', 'name')],
            'description' => ['nullable', 'string'],
            'type'        => ['required', Rule::in(array_column(PdvType::cases(), 'value'))],
            'status'      => ['required', Rule::in(array_column(PdvStatus::cases(), 'value'))],
            'street'      => ['nullable', 'string', 'max:255'],
            'number'      => ['nullable', 'string', 'max:50'],
            'complement'  => ['nullable', 'string', 'max:255'],

            'photos'      => ['nullable', 'array'],
            'photos.*'    => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],

            'videos'      => ['nullable', 'array'],
            'videos.*'    => ['mimetypes:video/mp4,video/avi,video/mpeg', 'max:20480'],
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
            $errorMessage = app()->environment('local')
                ? 'Erro: ' . $e->getMessage()
                : 'Ocorreu um erro ao cadastrar o Ponto de Venda. Tente novamente.';

            return back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    public function show(Pdv $pdv)
    {
        $pdv->load('equipments');

        // 2. Busca os IDs Externos UMA ÚNICA VEZ.
        $externalIdRecords = ExternalId::forItem($pdv->id)->latest()->get();

        // 3. Busca equipamentos que ainda não estão associados a nenhum PDV.
        $availableEquipments = Equipment::whereDoesntHave('pdvs')->get();

        // 4. Envia TODOS os dados necessários para a view.
        return view('pdvs.show', [
            'pdv' => $pdv,
            'availableEquipments' => $availableEquipments,
            'externalIdRecords' => $externalIdRecords, // <-- A nova variável que estamos passando
        ]);
    }

    public function edit(Pdv $pdv): View
    {
        return view('pdvs.edit', compact('pdv'));
    }

    public function update(Request $request, Pdv $pdv): RedirectResponse
    {
        $validatedData = $request->validate([
            'name'        => ['required', 'string', 'max:255', Rule::unique('pdvs', 'name')->ignore($pdv->id)],
            'description' => ['nullable', 'string'],
            'type'        => ['required', 'string', 'max:255'],
            'status'      => ['required', 'string', 'max:255'],
            'street'      => ['nullable', 'string', 'max:255'],
            'number'      => ['nullable', 'string', 'max:50'],
            'complement'  => ['nullable', 'string', 'max:255'],

            'photos'      => ['nullable', 'array'],
            'photos.*'    => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],

            'videos'      => ['nullable', 'array'],
            'videos.*'    => ['mimetypes:video/mp4,video/avi,video/mpeg', 'max:20480'],
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
                ->with('error', 'Ocorreu um erro ao atualizar o Ponto de Venda. Tente novamente.')
                ->withInput();
        }
    }

    public function destroy(Pdv $pdv): RedirectResponse
    {
        try {
            $pdv->delete();

            return redirect()
                ->route('pdvs.index')
                ->with('success', 'Ponto de Venda excluído com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao excluir PDV: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()
                ->with('error', 'Ocorreu um erro ao excluir o Ponto de Venda. Tente novamente.');
        }
    }

    public function attachEquipment(Request $request, Pdv $pdv): RedirectResponse
    {
        $data = $request->validate([
            'equipments'   => ['required', 'array', 'min:1'],
            'equipments.*' => ['uuid', 'exists:equipments,id'],
        ]);

        return DB::transaction(function () use ($pdv, $data) {
            // Garante que só iremos anexar os que ainda estão "Disponível"
            $toAttach = Equipment::available()
                ->whereIn('id', $data['equipments'])
                ->pluck('id')
                ->all();

            if (empty($toAttach)) {
                return back()->with('error', 'Nenhum equipamento disponível para associar.');
            }

            $pdv->equipments()->syncWithoutDetaching($toAttach);

            // Atualiza o status para "Em uso"
            Equipment::whereIn('id', $toAttach)->update(['status' => 'Em uso']);

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

            // Se não estiver mais associado a NENHUM PDV, volta para "Disponível"
            if (!$equipment->pdvs()->exists()) {
                $equipment->update(['status' => 'Disponível']);
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
            // Pega os caminhos das fotos existentes ou um array vazio
            $photoPaths = $pdv->photos ?? [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photoPaths[] = $photo->store('pdv_photos', 'public');
                }
            }

            // Pega os caminhos dos vídeos existentes ou um array vazio
            $videoPaths = $pdv->videos ?? [];
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $videoPaths[] = $video->store('pdv_videos', 'public');
                }
            }

            // Atualiza o PDV com os arrays de mídia combinados
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

    /**
     * Remove uma mídia específica (foto ou vídeo) de um PDV.
     */
    public function destroyMedia(Pdv $pdv, string $type, int $index): RedirectResponse
    {
        try {
            $mediaArray = $pdv->$type ?? [];

            if (isset($mediaArray[$index])) {
                // 1. Pega o caminho do arquivo para deletar do disco
                $filePathToDelete = $mediaArray[$index];

                // 2. Remove o item do array
                unset($mediaArray[$index]);

                // 3. Deleta o arquivo do armazenamento (storage/app/public/...)
                Storage::disk('public')->delete($filePathToDelete);

                // 4. Atualiza o PDV com o novo array (com índices reorganizados)
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
