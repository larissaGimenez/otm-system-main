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
        $client->load([
            'pdvs' => fn ($q) => $q->orderBy('name'),
            'contracts' => fn ($q) => $q->with('monthlySales')->orderByDesc('signed_at'),
            'activationFee.installments' => fn ($q) => $q->orderBy('installment_number'),
            'contacts' => fn ($q) => $q->orderBy('name'),
        ])->loadCount(['pdvs', 'contracts', 'contacts']);

        $availablePdvs = Pdv::whereNull('client_id')->orderBy('name')->get();

        $pdvCount          = $client->pdvs_count;      
        $contractCount     = $client->contracts_count;  
        $contactCount    = $client->contacts_count;
        $installmentsCount = $client->activationFee?->installments()->count() ?? 0;

        return view('clients.show', compact(
            'client',
            'availablePdvs',
            'pdvCount',
            'contractCount',
            'contactCount',
            'installmentsCount'
        ));
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

    public function attachPdv(Request $request, Client $client): RedirectResponse
    {
        $data = $request->validate([
            'pdv_id' => ['required', 'uuid', Rule::exists('pdvs', 'id')],
        ]);

        $pdv = Pdv::findOrFail($data['pdv_id']);

        // Se já estiver associado a outro cliente, bloqueia
        if ($pdv->client_id && $pdv->client_id !== $client->id) {
            return back()->with('error', 'Este PDV já está associado a outro cliente.');
        }

        $pdv->client()->associate($client);
        $pdv->save();

        return back()->with('success', 'PDV associado com sucesso.');
    }

    public function detachPdv(Client $client, Pdv $pdv): RedirectResponse
    {
        // Garante que o PDV pertence a este cliente
        if ($pdv->client_id !== $client->id) {
            return back()->with('error', 'Este PDV não está associado a este cliente.');
        }

        $pdv->client()->dissociate();
        $pdv->save();

        return back()->with('success', 'PDV desassociado com sucesso.');
    }
}