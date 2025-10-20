<?php

namespace App\Http\Controllers;

use App\Models\Request;
use App\Models\Area;
use App\Models\User;
use App\Enums\Request\RequestPriority;
use App\Enums\Request\RequestStatus;
use App\Enums\Request\RequestType;
use Illuminate\Http\Request as HttpRequest; // Renomeado para evitar conflito
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * Lista os chamados de acordo com as permissões do usuário.
     */
    public function index(HttpRequest $request): View
    {
        // Autoriza a visualização da lista (método viewAny da Policy)
        $this->authorize('viewAny', Request::class);

        $query = Request::with(['area', 'requester', 'assignees']); // Eager load

        // Lógica de filtro para diferentes roles
        $user = Auth::user();
        if (!$user->hasRole('admin')) {
            // Se não for admin, mostra apenas os chamados criados por ele
            // OU os chamados da(s) área(s) a que ele pertence.
            $user->loadMissing('teams.area'); // Carrega áreas das equipes do usuário
            $userAreaIds = $user->teams->pluck('area_id')->filter()->unique()->all();

            $query->where(function ($q) use ($user, $userAreaIds) {
                $q->where('requester_id', $user->id) // Chamados criados por ele
                  ->orWhereIn('area_id', $userAreaIds); // Chamados das suas áreas
            });
        }

        // Lógica de busca
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm)
                  ->orWhereRelation('area', 'name', 'like', $searchTerm) // Busca pelo nome da área
                  ->orWhereRelation('requester', 'name', 'like', $searchTerm); // Busca pelo nome do requisitante
            });
        }

        $requests = $query->latest()->paginate(15);

        return view('requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Autoriza a criação (método create da Policy)
        $this->authorize('create', Request::class);

        $areas = Area::orderBy('name')->get(); // Pega todas as áreas para o select

        return view('requests.create', [
            'areas'     => $areas,
            'types'     => RequestType::cases(),
            'priorities'=> RequestPriority::cases(),
            'statuses'  => RequestStatus::cases(), // Ou apenas os status iniciais permitidos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HttpRequest $request): RedirectResponse
    {
        // Autoriza a criação
        $this->authorize('create', Request::class);

        $validatedData = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'area_id'     => ['required', 'uuid', 'exists:areas,id'],
            'type'        => ['required', Rule::in(array_column(RequestType::cases(), 'value'))],
            'priority'    => ['required', Rule::in(array_column(RequestPriority::cases(), 'value'))],
            'status'      => ['required', Rule::in(array_column(RequestStatus::cases(), 'value'))], // Permitir status inicial?
            'due_at'      => ['nullable', 'date'],
        ]);

        try {
            // Adiciona o ID do usuário logado como requester
            $validatedData['requester_id'] = Auth::id();

            Request::create($validatedData);

            return redirect()->route('requests.index')
                             ->with('success', 'Chamado aberto com sucesso.');

        } catch (\Throwable $e) {
            Log::error('Falha ao criar chamado: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao abrir o chamado.';
            return back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Display the specified resource.
     * Segue nosso padrão: carrega tudo aqui para a view componentizada.
     */
    public function show(Request $request): View // Laravel faz a busca pelo UUID automaticamente
    {
        // Autoriza a visualização deste chamado específico (método view da Policy)
        $this->authorize('view', $request);

        // Carrega os relacionamentos necessários para a view
        $request->load(['area', 'requester', 'assignees']);

        // Busca usuários que podem ser designados (membros da área do chamado)
        // Otimização: Carrega 'users.teams' para evitar N+1 na Policy
        $areaWithUsers = Area::with('teams.users')->find($request->area_id);
        $availableAssignees = collect();
        if ($areaWithUsers) {
             $availableAssignees = $areaWithUsers->teams->flatMap->users->unique('id')->sortBy('name');
        }

        return view('requests.show', [
            'request'            => $request,
            'availableAssignees' => $availableAssignees,
            // Passar os Enums para os componentes, se necessário (ex: mudar status)
            'statuses'           => RequestStatus::cases(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): View
    {
        // Autoriza a edição (método update da Policy)
        $this->authorize('update', $request);

        $areas = Area::orderBy('name')->get();

        return view('requests.edit', [
            'request'   => $request,
            'areas'     => $areas,
            'types'     => RequestType::cases(),
            'priorities'=> RequestPriority::cases(),
            'statuses'  => RequestStatus::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $updateRequest, Request $request): RedirectResponse // Renomeado para clareza
    {
        // Autoriza a edição
        $this->authorize('update', $request);

        $validatedData = $updateRequest->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'area_id'     => ['required', 'uuid', 'exists:areas,id'],
            'type'        => ['required', Rule::in(array_column(RequestType::cases(), 'value'))],
            'priority'    => ['required', Rule::in(array_column(RequestPriority::cases(), 'value'))],
            'status'      => ['required', Rule::in(array_column(RequestStatus::cases(), 'value'))],
            'due_at'      => ['nullable', 'date'],
        ]);

        try {
            $request->update($validatedData);

            // Redireciona para a página de detalhes após a edição
            return redirect()->route('requests.show', $request)
                             ->with('success', 'Chamado atualizado com sucesso.');

        } catch (\Throwable $e) {
            Log::error('Falha ao atualizar chamado: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao atualizar o chamado.';
            return back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Autoriza a exclusão (método delete da Policy)
        $this->authorize('delete', $request);

        try {
            $request->delete(); // Soft delete
            return redirect()->route('requests.index')
                             ->with('success', 'Chamado excluído com sucesso.');
        } catch (\Throwable $e) {
            Log::error('Falha ao excluir chamado: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao excluir o chamado.';
            return back()->with('error', $errorMessage);
        }
    }

    // ----------- MÉTODOS PARA GERENCIAR RESPONSÁVEIS (ASSIGNEES) -----------

    /**
     * Assign one or more users to the request.
     */
    public function assignUsers(HttpRequest $assignRequest, Request $request): RedirectResponse
    {
        // Autoriza a ação de atribuir (método assign da Policy)
        $this->authorize('assign', $request);

        $validated = $assignRequest->validate([
            'assignees'   => ['required', 'array', 'min:1'],
            'assignees.*' => ['uuid', 'exists:users,id'],
        ]);

        try {
            // Verifica se os usuários pertencem à área do chamado (segurança extra)
            $areaUserIds = $this->getAreaUserIds($request->area_id);
            $usersToAssign = collect($validated['assignees'])->filter(function ($userId) use ($areaUserIds) {
                return in_array($userId, $areaUserIds);
            })->all();

            if (empty($usersToAssign)) {
                return back()->with('error', 'Nenhum dos usuários selecionados pertence à área deste chamado.');
            }

            // syncWithoutDetaching para não remover responsáveis existentes se adicionar mais
            $request->assignees()->syncWithoutDetaching($usersToAssign);

            // Opcional: Mudar status para 'Em Andamento' se estava 'Aberto'
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

    /**
     * Unassign a user from the request.
     */
    public function unassignUser(Request $request, User $user): RedirectResponse
    {
        // Autoriza a ação de atribuir/desatribuir
        $this->authorize('assign', $request);

        try {
            $request->assignees()->detach($user->id);

            // Opcional: Voltar status para 'Aberto' se não houver mais responsáveis?
            // if ($request->assignees()->count() === 0 && $request->status == RequestStatus::IN_PROGRESS) {
            //     $request->update(['status' => RequestStatus::OPEN]);
            // }

            return back()->with('success', 'Responsável removido com sucesso.');

        } catch (\Throwable $e) {
             Log::error('Falha ao remover responsável: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = app()->environment('local') ? 'Erro: ' . $e->getMessage() : 'Ocorreu um erro ao remover responsável.';
            return back()->with('error', $errorMessage);
        }
    }

    // ----------- MÉTODOS AUXILIARES -----------

    /**
     * Helper para buscar IDs de usuários pertencentes a uma área.
     */
    private function getAreaUserIds(string $areaId): array
    {
        $area = Area::with('teams.users')->find($areaId);
        if (!$area) {
            return [];
        }
        return $area->teams->flatMap->users->pluck('id')->unique()->all();
    }

}