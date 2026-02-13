<?php

namespace App\Http\Controllers;

use App\Models\Pdv;
use App\Models\PdvStatus;
use App\Models\PdvType;
use App\Models\Equipment;
use App\Models\ExternalId;
use App\Models\Client;
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
        $query = Pdv::query()
            ->with(['client', 'status', 'type']);

        if ($request->filled('status')) {
            $query->whereHas('status', function ($q) use ($request) {
                $q->where('slug', $request->input('status'));
            });
        }

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('street', 'like', $search)
                    ->orWhereHas('type', fn($q) => $q->where('name', 'like', $search))
                    ->orWhereHas('status', fn($q) => $q->where('name', 'like', $search))
                    ->orWhereHas('client', fn($q) => $q->where('name', 'like', $search));
            });
        }

        return view('pdvs.index', [
            'pdvs' => $query->latest()->paginate(10)->withQueryString(),
            'allStatuses' => PdvStatus::all(),
        ]);
    }

    public function create(): View
    {
        return view('pdvs.create', [
            'statuses' => PdvStatus::all(),
            'types' => PdvType::all(),
            'initialClients' => Client::whereDoesntHave('pdvs')
                ->orderBy('name')
                ->limit(5)
                ->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => ['required', 'uuid', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('pdvs', 'name')],
            'description' => ['nullable', 'string'],
            'pdv_type_id' => ['nullable', 'exists:pdv_types,id'],
            'pdv_status_id' => ['required', 'exists:pdv_statuses,id'],
            'street' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:50'],
            'complement' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $validated['slug'] = Str::slug($validated['name']);

            Pdv::create($validated);

            return redirect()
                ->route('pdvs.index')
                ->with('success', 'Ponto de Venda cadastrado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao criar PDV: ' . $e->getMessage(), ['trace' => $e->getTrace()]);
            return back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Pdv $pdv)
    {
        $pdv->load(['equipments', 'status', 'type', 'client']);

        // Buscar chamados de manutenção relacionados a este PDV
        $maintenanceRequests = \App\Models\Request::where('pdv_id', $pdv->id)
            ->where('type', \App\Enums\Request\RequestType::MANUTENCAO_PDV)
            ->with(['closedBy', 'requester'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pdvs.show', [
            'pdv' => $pdv,
            'availableEquipments' => Equipment::whereDoesntHave('pdvs')->get(),
            'externalIdRecords' => ExternalId::forItem($pdv->id)->latest()->get(),
            'maintenanceRequests' => $maintenanceRequests,
        ]);
    }

    public function edit(Pdv $pdv): View
    {
        return view('pdvs.edit', [
            'pdv' => $pdv,
            'statuses' => PdvStatus::all(),
            'types' => PdvType::all(),
        ]);
    }

    public function update(Request $request, Pdv $pdv): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('pdvs', 'name')->ignore($pdv->id)],
            'description' => ['nullable', 'string'],
            'pdv_type_id' => ['nullable', 'exists:pdv_types,id'],
            'pdv_status_id' => ['required', 'exists:pdv_statuses,id'],
            'street' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:50'],
            'complement' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $pdv->update($validated);

            return redirect()
                ->route('pdvs.index')
                ->with('success', 'Ponto de Venda atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar PDV: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar.')->withInput();
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
            Log::error('Erro ao excluir PDV: ' . $e->getMessage());
            return back()->with('error', 'Erro ao excluir.');
        }
    }


    public function addMedia(Request $request, Pdv $pdv): RedirectResponse
    {
        $validated = $request->validate([
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['mimetypes:video/mp4,video/avi,video/mpeg', 'max:20480'],
        ]);

        try {
            $photos = $pdv->photos ?? [];
            $videos = $pdv->videos ?? [];

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $photos[] = $photo->store('pdv_photos', 'public');
                }
            }

            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $videos[] = $video->store('pdv_videos', 'public');
                }
            }

            $pdv->update([
                'photos' => $photos,
                'videos' => $videos,
            ]);

            return back()->with('success', 'Mídia adicionada com sucesso!');
        } catch (\Throwable $e) {
            Log::error('Erro ao adicionar mídia ao PDV: ' . $e->getMessage());
            return back()->with('error', 'Erro ao enviar a mídia.');
        }
    }

    public function destroyMedia(Pdv $pdv, string $type, int $index): RedirectResponse
    {
        try {
            $media = $pdv->$type ?? [];

            if (!isset($media[$index])) {
                return back()->with('error', 'Arquivo não encontrado.');
            }

            Storage::disk('public')->delete($media[$index]);
            unset($media[$index]);

            $pdv->update([
                $type => array_values($media),
            ]);

            return back()->with('success', 'Mídia removida com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao remover mídia do PDV: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover mídia.');
        }
    }

    public function attachEquipment(Request $request, Pdv $pdv): RedirectResponse
    {
        $validated = $request->validate([
            'equipments' => ['required', 'array'],
            'equipments.*' => ['uuid', 'exists:equipments,id'],
        ]);

        try {
            foreach ($validated['equipments'] as $equipmentId) {
                if (!$pdv->equipments()->where('equipment_id', $equipmentId)->exists()) {
                    $pdv->equipments()->attach($equipmentId);
                }
            }

            return back()->with('success', 'Equipamentos associados com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao associar equipamentos ao PDV: ' . $e->getMessage());
            return back()->with('error', 'Erro ao associar equipamentos.');
        }
    }

    public function detachEquipment(Pdv $pdv, Equipment $equipment): RedirectResponse
    {
        try {
            $pdv->equipments()->detach($equipment->id);

            return back()->with('success', 'Equipamento removido com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Erro ao remover equipamento do PDV: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover equipamento.');
        }
    }

    public function checkName(Request $request)
    {
        $exists = Pdv::where('name', $request->input('name'))->exists();
        return response()->json(['exists' => $exists]);
    }
}
