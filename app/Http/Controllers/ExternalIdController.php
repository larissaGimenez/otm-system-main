<?php

namespace App\Http\Controllers;

use App\Models\ExternalId;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ExternalIdController extends Controller
{
    public function index(Request $request): View
    {
        $q = ExternalId::query();

        if ($request->filled('search')) {
            $term = '%'.$request->input('search').'%';

            $q->where(function ($s) use ($term) {
                $s->where('system_name', 'like', $term)
                  ->orWhere('external_id', 'like', $term)
                  ->orWhere('item_id', 'like', $term)
                  ->orWhere('item_type', 'like', $term);
            });
        }

        if ($request->filled('item_id')) {
            $q->where('item_id', $request->input('item_id'));
        }

        if ($request->filled('system_name')) {
            $q->where('system_name', $request->input('system_name'));
        }

        $externalIds = $q->latest()->paginate(15);

        return view('external_ids.index', compact('externalIds'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'item_id'     => ['required', 'uuid'],
            'item_type'   => ['required', 'string'],
            'system_name' => ['required', 'string', 'max:255'],
            'external_id' => ['required', 'string'],
        ]);

        try {
            ExternalId::create($data);

            return $this->redirectBackOrIndex()
                ->with('success', 'ID externo adicionado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao criar ExternalId: '.$e->getMessage());
            return back()->with('error', 'Não foi possível criar o ID externo.')
                         ->withInput();
        }
    }

    public function update(Request $request, ExternalId $externalId): RedirectResponse
    {
        $data = $request->validate([
            'item_id'     => ['required', 'uuid'],
            'item_type'   => ['required', 'string'],
            'system_name' => ['required', 'string', 'max:255'],
            'external_id' => ['required', 'string'],
        ]);

        try {
            $externalId->update($data);

            return $this->redirectBackOrIndex()
                ->with('success', 'ID externo atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar ExternalId: '.$e->getMessage());
            return back()->with('error', 'Não foi possível atualizar o ID externo.')
                         ->withInput();
        }
    }

    public function destroy(ExternalId $externalId): RedirectResponse
    {
        try {
            $externalId->delete();

            return $this->redirectBackOrIndex()
                ->with('success', 'ID externo removido com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao remover ExternalId: '.$e->getMessage());
            return back()->with('error', 'Não foi possível remover o ID externo.');
        }
    }

    private function redirectBackOrIndex(): RedirectResponse
    {
        return url()->previous()
            ? back()
            : redirect()->route('external-ids.index');
    }
}
