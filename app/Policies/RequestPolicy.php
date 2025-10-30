<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
{
    use HandlesAuthorization;

    /**
     * Regra "Mestra": Permite que Admins façam qualquer coisa,
     * ignorando todas as outras regras.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null; // Deixa as outras regras decidirem
    }

    /**
     * Determina se o usuário pode ver a listagem (index).
     */
    public function viewAny(User $user): bool
    {
        // Qualquer usuário logado pode ver a lista (o controller já filtra)
        return true; 
    }

    /**
     * Determina se o usuário pode ver um chamado específico.
     * (Não-admin só pode ver se for o criador ou membro da área)
     */
    public function view(User $user, Request $request): bool
    {
        // Carrega as equipes e áreas do usuário
        $user->loadMissing('teams.area'); 
        $userAreaIds = $user->teams->pluck('area_id')->filter()->unique()->all();

        return $user->id === $request->requester_id || // Ele criou?
               in_array($request->area_id, $userAreaIds); // Ele é da área?
    }

    /**
     * Determina se o usuário pode criar chamados.
     */
    public function create(User $user): bool
    {
        // Qualquer usuário logado pode criar
        return true; 
    }

    /**
     * Determina se o usuário pode ATUALIZAR (editar) o chamado.
     * REGRA: Apenas membros da área responsável. Admin (pelo 'before') pode.
     */
    public function update(User $user, Request $request): bool
    {
        // O 'before' já cuidou do admin.
        
        // Carrega as equipes e áreas do usuário
        $user->loadMissing('teams.area'); 
        $userAreaIds = $user->teams->pluck('area_id')->filter()->unique()->all();
        
        // Retorna true se o ID da área do chamado ESTIVER no array de IDs de área do usuário
        return in_array($request->area_id, $userAreaIds);
    }

    /**
     * Determina se o usuário pode DELETAR o chamado.
     * REGRA: Apenas Admin.
     */
    public function delete(User $user, Request $request): bool
    {
        // O 'before' (lá em cima) já retorna 'true' para o admin.
        // Se o usuário chegou até aqui, ele NÃO é admin.
        return false; 
    }

    /**
     * Determina se o usuário pode atribuir/remover responsáveis.
     */
    public function assign(User $user, Request $request): bool
    {
        // Mesma lógica do 'update': só membros da área podem atribuir.
        // (O Admin já foi liberado pelo 'before')
        return $this->update($user, $request);
    }

    /**
     * Determina se o usuário pode restaurar um chamado (soft delete).
     */
    public function restore(User $user, Request $request): bool
    {
        // Apenas admin (via 'before')
        return false; 
    }

    /**
     * Determina se o usuário pode deletar permanentemente.
     */
    public function forceDelete(User $user, Request $request): bool
    {
        // Apenas admin (via 'before')
        return false; 
    }
}