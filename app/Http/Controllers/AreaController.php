<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Team; // Importante para buscar times disponíveis
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AreaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Area::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                ->orWhere('slug', 'like', $searchTerm)
                ->orWhere('description', 'like', $searchTerm);
            });
        }

        $areas = $query->latest()->paginate(10);

        return view('areas.index', compact('areas'));
    }

    public function create(): View
    {
        return view('areas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('areas', 'name')],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Area::create($validated);

        return redirect()->route('areas.index')->with('success', 'Área criada com sucesso.');
    }

    /**
     * O MÉTODO PRINCIPAL PARA NOSSA ARQUITETURA.
     *
     * Prepara todos os dados necessários para a view de detalhes.
     */
    public function show(Area $area): View
    {
        // 1. Pré-carrega o relacionamento de times para evitar N+1 queries na view.
        $area->load('teams');

        // 2. Busca os times que ainda não têm uma área associada, para o modal "Adicionar Time".
        $availableTeams = Team::whereNull('area_id')->orderBy('name')->get();

        // 3. Envia todos os dados para a view.
        return view('areas.show', [
            'area' => $area,
            'availableTeams' => $availableTeams,
        ]);
    }

    public function edit(Area $area): View
    {
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('areas', 'name')->ignore($area->id)],
            'description' => ['nullable', 'string'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $area->update($validated);

        return redirect()->route('areas.index')->with('success', 'Área atualizada com sucesso.');
    }

    public function destroy(Area $area): RedirectResponse
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'Área excluída com sucesso.');
    }

    public function attachTeams(Request $request, Area $area): RedirectResponse
    {
        $validated = $request->validate([
            'teams' => 'required|array',
            'teams.*' => 'exists:teams,id',
        ]);

        Team::whereIn('id', $validated['teams'])->whereNull('area_id')->update(['area_id' => $area->id]);

        return back()->with('success', 'Equipe(s) associada(s) com sucesso.');
    }

    public function detachTeam(Area $area, Team $team): RedirectResponse
    {
        // Garante que o time realmente pertence a esta área antes de desassociar
        if ($team->area_id !== $area->id) {
            return back()->with('error', 'Esta equipe não pertence a esta área.');
        }

        $team->update(['area_id' => null]);

        return back()->with('success', 'Equipe desassociada com sucesso.');
    }
}