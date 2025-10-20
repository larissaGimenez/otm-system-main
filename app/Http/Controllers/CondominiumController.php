<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Condominium;
use App\Models\CondominiumContact;
use App\Enums\Condominium\CondominiumContactType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CondominiumController extends Controller
{
    /**
     * Lista todos os condomínios
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $condominiums = Condominium::query()
            ->search($search)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('condominium.index', compact('condominiums', 'search'));
    }

    /**
     * Exibe o formulário de criação
     */
    public function create()
    {
        return view('condominium.create');
    }

    /**
     * Armazena um novo condomínio
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'legal_name'         => ['required', 'string', 'max:255'],
            'cnpj'               => ['required', 'digits:14', 'unique:condominiums,cnpj'],
            'state_registration' => ['nullable', 'string', 'max:50'],
            'email'              => ['nullable', 'email', 'max:255', 'unique:condominiums,email'],
            'phone'              => ['nullable', 'string', 'max:50'],
            'postal_code'        => ['nullable', 'string', 'max:8'],
            'street'             => ['nullable', 'string', 'max:255'],
            'number'             => ['nullable', 'string', 'max:50'],
            'complement'         => ['nullable', 'string', 'max:255'],
            'neighborhood'       => ['nullable', 'string', 'max:255'],
            'city'               => ['nullable', 'string', 'max:255'],
            'state'              => ['nullable', 'string', 'max:2'],
            'logo'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'contract'           => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('condominiums/logos');
        }

        if ($request->hasFile('contract')) {
            $validated['contract_path'] = $request->file('contract')->store('condominiums/contracts');
        }

        $condominium = Condominium::create($validated);

        return redirect()
            ->route('condominiums.show', $condominium)
            ->with('success', 'Condomínio criado com sucesso.');
    }

    /**
     * Mostra os detalhes do condomínio
     */
    public function show(Condominium $condominium)
    {
        $condominium->load('contacts');

        return view('condominium.show', [
            'condominium' => $condominium,
            'contactTypes' => CondominiumContactType::cases(),
        ]);
    }

    /**
     * Exibe o formulário de edição
     */
    public function edit(Condominium $condominium)
    {
        return view('condominium.edit', compact('condominium'));
    }

    /**
     * Atualiza os dados do condomínio
     */
    public function update(Request $request, Condominium $condominium)
    {
        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'legal_name'         => ['required', 'string', 'max:255'],
            'cnpj'               => ['required', 'digits:14', Rule::unique('condominiums')->ignore($condominium->id)],
            'state_registration' => ['nullable', 'string', 'max:50'],
            'email'              => ['nullable', 'email', 'max:255', Rule::unique('condominiums')->ignore($condominium->id)],
            'phone'              => ['nullable', 'string', 'max:50'],
            'postal_code'        => ['nullable', 'string', 'max:8'],
            'street'             => ['nullable', 'string', 'max:255'],
            'number'             => ['nullable', 'string', 'max:50'],
            'complement'         => ['nullable', 'string', 'max:255'],
            'neighborhood'       => ['nullable', 'string', 'max:255'],
            'city'               => ['nullable', 'string', 'max:255'],
            'state'              => ['nullable', 'string', 'max:2'],
            'logo'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'contract'           => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        if ($request->hasFile('logo')) {
            if ($condominium->logo_path) Storage::delete($condominium->logo_path);
            $validated['logo_path'] = $request->file('logo')->store('condominiums/logos');
        }

        if ($request->hasFile('contract')) {
            if ($condominium->contract_path) Storage::delete($condominium->contract_path);
            $validated['contract_path'] = $request->file('contract')->store('condominiums/contracts');
        }

        $condominium->update($validated);

        return redirect()
            ->route('condominiums.show', $condominium)
            ->with('success', 'Condomínio atualizado com sucesso.');
    }

    /**
     * Exclui (soft delete)
     */
    public function destroy(Condominium $condominium)
    {
        $condominium->delete();

        return redirect()
            ->route('condominiums.index')
            ->with('success', 'Condomínio removido com sucesso.');
    }

    /**
     * Adiciona um contato (relacionamento)
     */
    public function storeContact(Request $request, Condominium $condominium)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'type'  => [Rule::enum(CondominiumContactType::class)],
        ]);

        $condominium->contacts()->create($validated);

        return redirect()
            ->route('condominiums.show', $condominium)
            ->with('success', 'Contato adicionado com sucesso.');
    }

    /**
     * Remove um contato
     */
    public function destroyContact(Condominium $condominium, CondominiumContact $contact)
    {
        abort_unless($contact->condominium_id === $condominium->id, 403, 'Contato não pertence a este condomínio.');
        $contact->delete();

        return redirect()
            ->route('condominiums.show', $condominium)
            ->with('success', 'Contato removido.');
    }
}
