<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index(Request $request): View
    {
        $query = Team::query();

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = '%' . $request->input('search') . '%';
            
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('status', 'like', 'searchTerm');
            });
        }

        $teams = $query->withTrashed()->latest()->paginate(10);

        return view('teams.index', compact('teams'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();
        return view('teams.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:'.Team::class],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'users' => ['nullable', 'array'], 
            'users.*' => ['exists:users,id'], 
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);

        try {
            DB::transaction(function () use ($validatedData, $request) {
                $team = Team::create($validatedData);

                if ($request->has('users')) {
                    $team->users()->sync($request->users);
                }
            });
            
            return redirect()->route('management.teams.index')
                             ->with('success', 'Equipe cadastrada com sucesso.');
        } catch (\Exception $e) {
            Log::error('Falha ao criar equipe: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Ocorreu um erro ao cadastrar a equipe. Tente novamente.')
                             ->withInput(); 
        }
    }

    public function show(Team $team): View
    {
        $team->load('users');

        $existingUserIds = $team->users->pluck('id');
        $availableUsers = User::whereNotIn('id', $existingUserIds)->orderBy('name')->get();

        return view('teams.show', compact('team', 'availableUsers'));
    }

    public function edit(Team $team): View
    {
        $users = User::orderBy('name')->get();
        return view('teams.edit', compact('team', 'users'));
    }

    public function update(Request $request, Team $team): RedirectResponse
    {
        // A validação continua a mesma e está perfeita.
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('teams')->ignore($team->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'users' => ['nullable', 'array'],
            'users.*' => ['exists:users,id'],
        ]);
        
        try {
            DB::transaction(function () use ($validatedData, $request, $team) {
                
                $teamData = \Illuminate\Support\Arr::except($validatedData, ['users']);
                $team->update($teamData);
                $team->users()->sync($request->users ?? []);
            });

            return redirect()->route('management.teams.index')
                            ->with('success', 'Equipe atualizada com sucesso.');
        } catch (\Exception $e) {
            Log::error('Falha ao atualizar equipe: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Ocorreu um erro ao atualizar a equipe. Tente novamente.')
                            ->withInput();
        }
    }

    public function destroy(Team $team): RedirectResponse
    {
        try {
            $team->delete();
            return redirect()->route('management.teams.index')
                             ->with('success', 'Equipe excluída com sucesso.');
        } catch (\Exception $e) {
            Log::error('Falha ao excluir equipe: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Ocorreu um erro ao excluir a equipe. Tente novamente.');
        }
    }

    public function removeUser(Request $request, Team $team, User $user): RedirectResponse
    {
        try {
            $team->users()->detach($user->id);
            return redirect()->back()->with('success', 'Membro removido da equipe com sucesso.');
        } catch (\Exception $e) {
            Log::error('Falha ao remover membro da equipe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao remover o membro. Tente novamente.');
        }
    }

    public function attachUsers(Request $request, Team $team): RedirectResponse
    {
        $request->validate([
            'users' => ['required', 'array'],
            'users.*' => ['exists:users,id'],
        ]);

        try {
            $team->users()->attach($request->users);
            return redirect()->back()->with('success', 'Membros adicionados à equipe com sucesso.');
        } catch (\Exception $e) {
            Log::error('Falha ao adicionar membros à equipe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro. Tente novamente.');
        }
    }
}