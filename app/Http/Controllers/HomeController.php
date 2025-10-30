<?php

namespace App\Http\Controllers;

use App\Models\Pdv;
use App\Models\Request as ServiceRequest; // Usando alias para evitar conflito
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial/dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();
        $stats = [];

        // Estatísticas de Chamados
        $stats['myOpenRequestsCount'] = ServiceRequest::where('requester_id', $user->id)
                                          ->whereIn('status', ['open', 'in_progress']) // Considera abertos e em andamento
                                          ->count();

        $stats['pendingAreaRequestsCount'] = 0;
        // Se for admin ou manager/staff, conta chamados pendentes nas suas áreas
        if ($user->role === 'admin' || in_array($user->role, ['manager', 'staff'])) {
             // Carrega as áreas das equipes do usuário
             $user->loadMissing('teams.area');
             $userAreaIds = $user->teams->pluck('area_id')->filter()->unique()->all();

             // Admin vê todos os chamados abertos
             if ($user->role === 'admin') {
                 $stats['pendingAreaRequestsCount'] = ServiceRequest::where('status', 'open')->count();
             } elseif (!empty($userAreaIds)) {
                // Staff/Manager veem apenas os das suas áreas
                 $stats['pendingAreaRequestsCount'] = ServiceRequest::whereIn('area_id', $userAreaIds)
                                                     ->where('status', 'open')
                                                     ->count();
             }
        }

        // Estatísticas de PDVs (Exemplo - ajuste conforme sua lógica de 'status')
        $stats['inactivePdvsCount'] = Pdv::where('status', 'inactive')->count(); // Exemplo

        return view('home', compact('user', 'stats'));
    }
}