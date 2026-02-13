<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Area;
use App\Models\User;
use App\Models\Pdv;
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

    public function index()
    {
        return view('requests.index');
    }

    public function create(): View
    {
        $this->authorize('create', Request::class);

        $areas = Area::orderBy('name')->get();
        $pdvs = Pdv::orderBy('name')->get();

        return view('requests.create', [
            'areas' => $areas,
            'pdvs' => $pdvs,
            'types' => RequestType::cases(),
            'priorities' => RequestPriority::cases(),
            'statuses' => RequestStatus::cases(),
        ]);
    }

    public function store(HttpRequest $request): RedirectResponse
    {
        $this->authorize('create', Request::class);

        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'area_id' => ['required', 'uuid', 'exists:areas,id'],
            'pdv_id' => ['nullable', 'uuid', 'exists:pdvs,id', 'required_if:type,manutencao_pdv'],
            'type' => ['required', Rule::in(array_column(RequestType::cases(), 'value'))],
            'priority' => ['required', Rule::in(array_column(RequestPriority::cases(), 'value'))],
            'status' => ['required', Rule::in(array_column(RequestStatus::cases(), 'value'))],
            'due_at' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,png,zip,doc,docx', 'max:10240'],
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

        $request->load(['area', 'requester', 'assignees', 'pdv']);

        $areaWithUsers = Area::with('teams.users')->find($request->area_id);
        $availableAssignees = collect();
        if ($areaWithUsers) {
            $availableAssignees = $areaWithUsers->teams->flatMap->users->unique('id')->sortBy('name');
        }

        return view('requests.show', [
            'request' => $request,
            'availableAssignees' => $availableAssignees,
            'statuses' => RequestStatus::cases(),
        ]);
    }

    public function edit(Request $request): View
    {
        $this->authorize('update', $request);

        $areas = Area::orderBy('name')->get();
        $pdvs = Pdv::orderBy('name')->get();

        return view('requests.edit', [
            'request' => $request,
            'areas' => $areas,
            'pdvs' => $pdvs,
            'types' => RequestType::cases(),
            'priorities' => RequestPriority::cases(),
            'statuses' => RequestStatus::cases(),
        ]);
    }

    public function update(HttpRequest $updateRequest, Request $request): RedirectResponse
    {
        $this->authorize('update', $request);

        $validatedData = $updateRequest->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'area_id' => ['required', 'uuid', 'exists:areas,id'],
            'pdv_id' => ['nullable', 'uuid', 'exists:pdvs,id', 'required_if:type,manutencao_pdv'],
            'type' => ['required', Rule::in(array_column(RequestType::cases(), 'value'))],
            'priority' => ['required', Rule::in(array_column(RequestPriority::cases(), 'value'))],
            'status' => ['required', Rule::in(array_column(RequestStatus::cases(), 'value'))],
            'due_at' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,jpg,png,zip,doc,docx', 'max:10240'],
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
            'assignees' => ['required', 'array', 'min:1'],
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

    public function start(Request $request): RedirectResponse
    {
        $this->authorize('update', $request);

        try {
            // Atualizar status
            $request->update(['status' => RequestStatus::IN_PROGRESS]);

            // Auto-atribuir usuário logado se não estiver atribuído
            if (!$request->assignees->contains(Auth::id())) {
                $request->assignees()->attach(Auth::id());
            }

            return back()->with('success', 'Atendimento iniciado e você foi atribuído ao chamado.');
        } catch (\Throwable $e) {
            Log::error('Falha ao iniciar atendimento: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao iniciar o atendimento.');
        }
    }

    public function showCloseForm(Request $request): View
    {
        $this->authorize('update', $request);

        if (!in_array($request->status, [RequestStatus::IN_PROGRESS, RequestStatus::LONG_SOLUTION])) {
            abort(403, 'Este chamado não pode ser fechado no status atual.');
        }

        return view('requests.close', ['request' => $request]);
    }

    public function close(HttpRequest $httpRequest, Request $request): RedirectResponse
    {
        $this->authorize('update', $request);

        $validatedData = $httpRequest->validate([
            'closure_description' => ['required', 'string', 'min:10'],
            'closure_media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,heic,mp4,mov,avi', 'max:20480'], // 20MB
        ]);

        try {
            $validatedData['closed_by'] = Auth::id();
            $validatedData['closed_at'] = now();
            $validatedData['status'] = RequestStatus::COMPLETED;

            if ($httpRequest->hasFile('closure_media')) {
                $file = $httpRequest->file('closure_media');
                $extension = $file->getClientOriginalExtension();

                // Determinar tipo de mídia
                $photoExtensions = ['jpg', 'jpeg', 'png', 'heic'];
                $validatedData['closure_media_type'] = in_array(strtolower($extension), $photoExtensions) ? 'photo' : 'video';

                // Salvar arquivo
                $validatedData['closure_media_path'] = $file->store('request_closures', 'public');
            }

            $request->update($validatedData);

            return redirect()->route('requests.show', $request)
                ->with('success', 'Chamado fechado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao fechar chamado: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao fechar o chamado.';
            return back()->with('error', $errorMessage)->withInput();
        }
    }

    public function archive(Request $request): RedirectResponse
    {
        $this->authorize('update', $request);

        if ($request->status !== RequestStatus::COMPLETED) {
            return back()->with('error', 'Apenas chamados concluídos podem ser arquivados.');
        }

        try {
            $request->archive();
            return back()->with('success', 'Chamado arquivado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao arquivar chamado: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao arquivar o chamado.');
        }
    }

    public function unarchive(Request $request): RedirectResponse
    {
        $this->authorize('update', $request);

        try {
            $request->unarchive();
            return redirect()->route('requests.show', $request)
                ->with('success', 'Chamado restaurado com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao restaurar chamado: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao restaurar o chamado.');
        }
    }

    public function archived(): View
    {
        $this->authorize('viewAny', Request::class);

        $requests = Request::archived()
            ->with(['area', 'requester', 'archivedBy'])
            ->orderBy('archived_at', 'desc')
            ->paginate(20);

        return view('requests.archived', ['requests' => $requests]);
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