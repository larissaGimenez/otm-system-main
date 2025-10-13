<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
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

        $dataToSave = $request->all();
        $dataToSave['password'] = Hash::make($request->password);
        $dataToSave['cpf'] = preg_replace('/\D/', '', $request->cpf);
        $dataToSave['phone'] = preg_replace('/\D/', '', $request->phone);
        $dataToSave['postal_code'] = preg_replace('/\D/', '', $request->postal_code);

        User::create($dataToSave);

        return redirect()->route('management.users.index')
                         ->with('success', 'Usuário cadastrado com sucesso.');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        // A validação continua a mesma
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

        // O bloco try...catch vai "tentar" executar o código
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

            // Se tudo der certo, redireciona com sucesso
            return redirect()->route('management.users.index')
                            ->with('success', 'Usuário atualizado com sucesso.');

        } catch (\Exception $e) {
            // Se qualquer erro (Exception) acontecer, o código entra aqui

            // 1. (Opcional, mas recomendado) Salva o erro real no log para você poder depurar
            Log::error('Falha ao atualizar usuário: ' . $e->getMessage());

            // 2. Redireciona o usuário de volta para o formulário com uma mensagem de erro amigável
            return redirect()->back()
                            ->with('error', 'Ocorreu um erro inesperado ao salvar as alterações. Por favor, tente novamente.')
                            ->withInput(); // withInput() garante que os dados digitados não sejam perdidos
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('management.users.index')
                             ->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $user->delete();

        return redirect()->route('management.users.index')
                         ->with('success', 'Usuário excluído com sucesso.');
    }
}