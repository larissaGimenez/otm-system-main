<?php

namespace App\Http\Controllers;

use App\Models\Pdv;
use App\Models\Client;

use App\Enums\Client\ClientType;
use App\Enums\General\GeneralBanks;  
use App\Enums\General\GeneralPixType;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::orderBy('name')->paginate(10); 
        return view('clients.index', compact('clients'));
    }

    public function create(): View  
    {
        return view('clients.create', [
            'types'     => ClientType::cases(),
            'banks'     => GeneralBanks::cases(),
            'pixTypes'  => GeneralPixType::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(array_column(ClientType::cases(), 'value'))],
            'cnpj' => 'required|string|size:14|unique:clients,cnpj',
            'postal_code' => 'nullable|string|size:8',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:50',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'bank'     => ['nullable', Rule::in(array_column(GeneralBanks::cases(), 'value'))],
            'agency'   => 'nullable|string|max:20',
            'account'  => 'nullable|string|max:20',
            'account_digit' => 'nullable|string|max:2',
            'pix_type' => ['nullable', Rule::in(array_column(GeneralPixType::cases(), 'value'))],
            'pix_key'  => 'nullable|string|max:255',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client): View
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', [
            'client'    => $client,
            'types'     => ClientType::cases(),
            'banks'     => GeneralBanks::cases(),
            'pixTypes'  => GeneralPixType::cases(),
        ]);
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(array_column(ClientType::cases(), 'value'))],
            'cnpj' => ['required', 'string', 'size:14', Rule::unique('clients')->ignore($client->id)],
            'postal_code' => 'nullable|string|size:8',
            'street' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:50',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|size:2',
            'bank'     => ['nullable', Rule::in(array_column(GeneralBanks::cases(), 'value'))],
            'agency'   => 'nullable|string|max:20',
            'account'  => 'nullable|string|max:20',
            'account_digit' => 'nullable|string|max:2',
            'pix_type' => ['nullable', Rule::in(array_column(GeneralPixType::cases(), 'value'))],
            'pix_key'  => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }
}
