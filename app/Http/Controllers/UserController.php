<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Enums\User\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = '%' . $request->input('search') . '%';
            
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm);
            });
        }

        $users = $query->withTrashed()->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = UserRole::cases();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', Rule::in(['admin', 'manager', 'staff', 'field'])],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cpf' => ['nullable', 'string', 'max:14', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'postal_code' => ['nullable', 'string', 'max:9'],
            'street' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:50'],
            'complement' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:2'],
        ]);

        try {
            $validatedData['password'] = Hash::make($request->password);
            $validatedData['cpf'] = preg_replace('/\D/', '', $request->cpf);
            $validatedData['phone'] = preg_replace('/\D/', '', $request->phone);
            $validatedData['postal_code'] = preg_replace('/\D/', '', $request->postal_code);

            User::create($validatedData);

            return redirect()->route('management.users.index')
                             ->with('success', 'Usuário cadastrado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Falha ao criar usuário: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Ocorreu um erro ao cadastrar o usuário. Tente novamente.')
                             ->withInput();
        }
    }

    public function show(User $user): View
    {
        $existingTeamIds = $user->teams->pluck('id');
        $availableTeams = Team::whereNotIn('id', $existingTeamIds)->orderBy('name')->get();
        return view('users.show', compact('user', 'availableTeams'));
    }

    public function edit(User $user): View
    {
        $roles = UserRole::cases();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'manager', 'staff', 'field'])],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'cpf' => ['nullable', 'string', 'max:14', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'postal_code' => ['nullable', 'string', 'max:9'],
            'street' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:50'],
            'complement' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:2'],
        ]);

        try {
            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }
            
            $validatedData['cpf'] = preg_replace('/\D/', '', $request->cpf);
            $validatedData['phone'] = preg_replace('/\D/', '', $request->phone);
            $validatedData['postal_code'] = preg_replace('/\D/', '', $request->postal_code);
            
            $user->update($validatedData);

            return redirect()->route('management.users.index')
                             ->with('success', 'Usuário atualizado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Falha ao atualizar usuário: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Ocorreu um erro ao salvar as alterações. Tente novamente.')
                             ->withInput();
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('management.users.index')
                             ->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        try {
            $user->delete();
            return redirect()->route('management.users.index')
                             ->with('success', 'Usuário excluído com sucesso.');
        } catch (\Exception $e) {
            Log::error('Falha ao excluir usuário: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Ocorreu um erro ao excluir o usuário. Tente novamente.');
        }
    }

    public function attachTeams(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'teams' => ['required', 'array'],
            'teams.*' => ['exists:teams,id'],
        ]);

        try {
            $user->teams()->attach($request->teams);
            return redirect()->route('management.users.show', $user)
                            ->with('success', 'Usuário adicionado às equipes com sucesso.');
        } catch (\Exception $e) {
            Log::error('Falha ao adicionar usuário a equipes: ' . $e->getMessage());

            return redirect()->route('management.users.show', $user)
                            ->with('error', 'Ocorreu um erro. Tente novamente.');
        }
    }

    public function detachTeam(Request $request, User $user, Team $team): RedirectResponse
    {
        try {
            $user->teams()->detach($team->id);
   
            return redirect()->route('management.users.show', $user)
                            ->with('success', "Usuário removido da equipe '{$team->name}' com sucesso.");
        } catch (\Exception $e) {
            Log::error("Falha ao remover usuário da equipe '{$team->name}': " . $e->getMessage());

            return redirect()->route('management.users.show', $user)
                            ->with('error', 'Ocorreu um erro. Tente novamente.');
        }
    }
}