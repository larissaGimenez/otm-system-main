<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization; // Importante

class RequestPolicy
{
    // O trait HandlesAuthorization é útil, embora possamos não usá-lo diretamente aqui.
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * Define quem pode ver a lista de chamados (index).
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        // Qualquer usuário logado pode ver *alguma* lista (a lógica de filtro fica no Controller)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Define quem pode ver os detalhes de UM chamado específico.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Request  $request
     * @return bool
     */
    public function view(User $user, Request $request): bool
    {
        // 1. Admin pode ver tudo.
        if ($user->hasRole('admin')) {
            return true;
        }

        // 2. O criador do chamado pode ver seu próprio chamado.
        if ($user->id === $request->requester_id) {
            return true;
        }

        // 3. Membros da área do chamado podem ver o chamado.
        if ($this->isUserMemberOfRequestArea($user, $request)) {
            return true;
        }

        // Se nenhuma das condições acima for atendida, nega o acesso.
        return false;
    }

    /**
     * Determine whether the user can create models.
     * Define quem pode abrir um novo chamado.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Qualquer usuário autenticado pode criar um chamado.
        return true;
    }

    /**
     * Determine whether the user can update the model.
     * Define quem pode editar um chamado (ex: mudar prioridade, descrição).
     * Simplificado por enquanto: Admin ou Membro da Área.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Request  $request
     * @return bool
     */
    public function update(User $user, Request $request): bool
    {
        // 1. Admin pode editar.
        if ($user->hasRole('admin')) {
            return true;
        }

        // 2. Membros da área podem editar (poderia ser refinado com base no status depois).
        if ($this->isUserMemberOfRequestArea($user, $request)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Define quem pode excluir um chamado. Simplificado: Apenas Admin por segurança.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Request  $request
     * @return bool
     */
    public function delete(User $user, Request $request): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model. (Opcional - Soft Deletes)
     */
    public function restore(User $user, Request $request): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model. (Opcional - Soft Deletes)
     */
    public function forceDelete(User $user, Request $request): bool
    {
        return $user->hasRole('admin');
    }

    // ----------- MÉTODOS CUSTOMIZADOS -----------

    /**
     * Determine whether the user can assign users to the request.
     * Define quem pode adicionar/remover responsáveis.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Request  $request
     * @return bool
     */
    public function assign(User $user, Request $request): bool
    {
        // 1. Admin pode atribuir.
        if ($user->hasRole('admin')) {
            return true;
        }

        // 2. Membros da área podem atribuir.
        if ($this->isUserMemberOfRequestArea($user, $request)) {
            return true;
        }

        return false;
    }

    // ----------- MÉTODOS AUXILIARES -----------

    /**
     * Verifica se o usuário pertence a alguma equipe associada à área do chamado.
     * Esta é a lógica central para "Membro da Área".
     *
     * @param  User  $user
     * @param  Request  $request
     * @return bool
     */
    protected function isUserMemberOfRequestArea(User $user, Request $request): bool
    {
        // Carrega as equipes do usuário UMA VEZ para otimização
        $user->loadMissing('teams');

        // Verifica se ALGUMA das equipes do usuário está associada à área do chamado
        return $user->teams->contains(function ($team) use ($request) {
            return $team->area_id === $request->area_id;
        });
    }
}