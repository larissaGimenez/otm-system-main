<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Request $request): bool
    {
        // CORREÇÃO AQUI: Verifica a coluna 'role'
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->id === $request->requester_id) {
            return true;
        }

        if ($this->isUserMemberOfRequestArea($user, $request)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Request $request): bool
    {
        // CORREÇÃO AQUI: Verifica a coluna 'role'
        if ($user->role === 'admin') {
            return true;
        }

        if ($this->isUserMemberOfRequestArea($user, $request)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Request $request): bool
    {
        // CORREÇÃO AQUI: Verifica a coluna 'role'
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Request $request): bool
    {
        // CORREÇÃO AQUI: Verifica a coluna 'role'
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Request $request): bool
    {
        // CORREÇÃO AQUI: Verifica a coluna 'role'
        return $user->role === 'admin';
    }

    // ----------- MÉTODOS CUSTOMIZADOS -----------

    /**
     * Determine whether the user can assign users to the request.
     */
    public function assign(User $user, Request $request): bool
    {
        // CORREÇÃO AQUI: Verifica a coluna 'role'
        if ($user->role === 'admin') {
            return true;
        }

        if ($this->isUserMemberOfRequestArea($user, $request)) {
            return true;
        }

        return false;
    }

    // ----------- MÉTODOS AUXILIARES -----------

    /**
     * Verifica se o usuário pertence a alguma equipe associada à área do chamado.
     */
    protected function isUserMemberOfRequestArea(User $user, Request $request): bool
    {
        $user->loadMissing('teams');
        return $user->teams->contains(fn ($team) => $team->area_id === $request->area_id);
    }
}