<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Area;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Enums\Request\RequestPriority;
use App\Enums\Request\RequestStatus;
use App\Enums\Request\RequestType;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RequestController extends Controller
{
    use AuthorizesRequests;

    public function index(HttpRequest $request): View
    {
        $this->authorize('viewAny', Request::class);

        $query = Request::with(['area', 'requester', 'assignees']);

        $user = Auth::user();
        if ($user->role !== 'admin') {
            $user->loadMissing('teams.area');
            $userAreaIds = $user->teams->pluck('area_id')->filter()->unique()->all();

            $query->where(function ($q) use ($user, $userAreaIds) {
                $q->where('requester_id', $user->id)
                    ->orWhereIn('area_id', $userAreaIds);
            });
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm)
                    ->orWhereRelation('area', 'name', 'like', $searchTerm)
                    ->orWhereRelation('requester', 'name', 'like', $searchTerm);
            });
        }

        $requests = $query->latest()->paginate(15);

        return view('requests.index', compact('requests'));
    }

    public function create(): View
    {
        $this->authorize('create', Request::class);

        $areas = Area::orderBy('name')->get();

        return view('requests.create', [
            'areas'      => $areas,
            'types'      => RequestType::cases(),
            'priorities' => RequestPriority::cases(),
            'statuses'   => RequestStatus::cases(),
        ]);
    }

    public function store(HttpRequest $request): RedirectResponse
    {
        $this->authorize('create', Request::class);

        $validatedData = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'area_id'     => ['required', 'uuid', 'exists:areas,id'],
            'type'        => ['required', Rule::in(array_column(RequestType::cases(), 'value'))],
            'priority'    => ['required', Rule::in(array_column(RequestPriority::cases(), 'value'))],
            'status'      => ['required', Rule::in(array_column(RequestStatus::cases(), 'value'))],
            'due_at'      => ['nullable', 'date'],
            'attachment'  => ['nullable', 'file', 'mimes:pdf,jpg,png,zip,doc,docx', 'max:10240'],
        ]);

        try {
            $validatedData['requester_id'] = Auth::id();

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $validatedData['attachment_original_name'] = $file->getClientOriginalName();
                $validatedData['attachment_path'] = $file->store('request_attachments', 'public');
            }

            Request::create($validatedData);

            return redirect()->route('requests.index')
                ->with('success', 'Chamado aberto com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao criar chamado: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao abrir o chamado.';
            return back()->with('error', $errorMessage)->withInput();
        }
    }

    public function show(Request $request): View
    {
        $this->authorize('view', $request);

        $request->load(['area', 'requester', 'assignees']);

        $areaWithUsers = Area::with('teams.users')->find($request->area_id);
        $availableAssignees = collect();
        if ($areaWithUsers) {
            $availableAssignees = $areaWithUsers->teams->flatMap->users->unique('id')->sortBy('name');
        }

        return view('requests.show', [
            'request'            => $request,
            'availableAssignees' => $availableAssignees,
            'statuses'           => RequestStatus::cases(),
        ]);
    }

    public function edit(Request $request): View
    {
        $this->authorize('update', $request);

        $areas = Area::orderBy('name')->get();

        return view('requests.edit', [
            'request'    => $request,
            'areas'      => $areas,
            'types'      => RequestType::cases(),
            'priorities' => RequestPriority::cases(),
            'statuses'   => RequestStatus::cases(),
        ]);
    }

    public function update(HttpRequest $updateRequest, Request $request): RedirectResponse
    {
        $this->authorize('update', $request);

        $validatedData = $updateRequest->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'area_id'     => ['required', 'uuid', 'exists:areas,id'],
            'type'        => ['required', Rule::in(array_column(RequestType::cases(), 'value'))],
            'priority'    => ['required', Rule::in(array_column(RequestPriority::cases(), 'value'))],
            'status'      => ['required', Rule::in(array_column(RequestStatus::cases(), 'value'))],
            'due_at'      => ['nullable', 'date'],
            'attachment'  => ['nullable', 'file', 'mimes:pdf,jpg,png,zip,doc,docx', 'max:10240'],
        ]);

        try {
            if ($updateRequest->hasFile('attachment')) {
                if ($request->attachment_path) {
                    Storage::disk('public')->delete($request->attachment_path);
                }

                $file = $updateRequest->file('attachment');
                $validatedData['attachment_original_name'] = $file->getClientOriginalName();
                $validatedData['attachment_path'] = $file->store('request_attachments', 'public');
            }

            $request->update($validatedData);

            return redirect()->route('requests.show', $request)
                ->with('success', 'Chamado atualizado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar chamado: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao atualizar o chamado.';
            return back()->with('error', $errorMessage)->withInput();
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->authorize('delete', $request);

        try {
            $request->delete();
            return redirect()->route('requests.index')
                ->with('success', 'Chamado excluído com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao excluir chamado: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao excluir o chamado.';
            return back()->with('error', $errorMessage);
        }
    }

    public function assignUsers(HttpRequest $assignRequest, Request $request): RedirectResponse
    {
        $this->authorize('assign', $request);

        $validated = $assignRequest->validate([
            'assignees'   => ['required', 'array', 'min:1'],
            'assignees.*' => ['uuid', 'exists:users,id'],
        ]);

        try {
            $areaUserIds = $this->getAreaUserIds($request->area_id);
            $usersToAssign = collect($validated['assignees'])->filter(function ($userId) use ($areaUserIds) {
                return in_array($userId, $areaUserIds);
            })->all();

            if (empty($usersToAssign)) {
                return back()->with('error', 'Nenhum dos usuários selecionados pertence à área deste chamado.');
            }

            $request->assignees()->syncWithoutDetaching($usersToAssign);

            if ($request->status == RequestStatus::OPEN) {
                $request->update(['status' => RequestStatus::IN_PROGRESS]);
            }

            return back()->with('success', 'Responsável(is) adicionado(s) com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao atribuir responsável: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao atribuir responsável(is).';
            return back()->with('error', $errorMessage);
        }
    }

    public function unassignUser(Request $request, User $user): RedirectResponse
    {
        $this->authorize('assign', $request);

        try {
            $request->assignees()->detach($user->id);

            return back()->with('success', 'Responsável removido com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao remover responsável: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao remover responsável.';
            return back()->with('error', $errorMessage);
        }
    }

    private function getAreaUserIds(string $areaId): array
    {
        $area = Area::with('teams.users')->find($areaId);
        if (!$area) {
            return [];
        }
        return $area->teams->flatMap->users->pluck('id')->unique()->all();
    }
}   