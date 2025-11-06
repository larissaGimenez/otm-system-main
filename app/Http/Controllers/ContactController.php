<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contact;
use App\Enums\Contact\ContactType; // Importe o Enum
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Armazena um novo contato para um cliente.
     */
    public function store(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'type'            => ['required', Rule::in(array_column(ContactType::cases(), 'value'))],
            'email'           => ['nullable', 'email', 'max:255'],
            'phone_primary'   => ['nullable', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'notes'           => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            // Adiciona o client_id e cria o contato
            $client->contacts()->create($validated);

            return back()->with('success', 'Contato adicionado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao criar contato: ' . $e->getMessage());
            return back()->with('error', 'Erro ao adicionar o contato.')->withInput();
        }
    }

    /**
     * Atualiza um contato existente.
     */
    public function update(Request $request, Contact $contact): RedirectResponse
    {
        // Autorização (Opcional, mas recomendado)
        // $this->authorize('update', $contact); 

        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:100'],
            'type'            => ['required', Rule::in(array_column(ContactType::cases(), 'value'))],
            'email'           => ['nullable', 'email', 'max:255'],
            'phone_primary'   => ['nullable', 'string', 'max:20'],
            'phone_secondary' => ['nullable', 'string', 'max:20'],
            'notes'           => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $contact->update($validated);
            return back()->with('success', 'Contato atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar contato: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar o contato.')->withInput();
        }
    }

    /**
     * Remove um contato.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        // Autorização (Opcional)
        // $this->authorize('delete', $contact);

        try {
            $contact->delete(); // Soft delete
            return back()->with('success', 'Contato removido com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao remover contato: ' . $e->getMessage());
            return back()->with('error', 'Erro ao remover o contato.');
        }
    }
}