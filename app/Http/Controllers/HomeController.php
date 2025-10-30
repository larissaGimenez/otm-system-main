<?php

namespace App\Http\Controllers;

use App\Models\Pdv;
use App\Models\Request as ServiceRequest; 
use Illuminate\Http\Request as HttpRequest;
use App\Enums\Request\RequestStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon; // <-- Importar o Carbon

class HomeController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // --- 1. DADOS DO USUÁRIO LOGADO (Prioridade) ---
        // Busca os 10 chamados mais recentes atribuídos ao usuário
        $myAssignedRequests = ServiceRequest::whereHas('assignees', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn('status', [RequestStatus::OPEN, RequestStatus::IN_PROGRESS])
            ->with('area', 'requester') 
            ->latest('updated_at')
            ->limit(10)
            ->get();

        // --- 2. STATS DO USUÁRIO ---
        $stats = [];
        
        // Card: Meus Chamados Solicitados (criados por mim)
        $stats['myOpenRequestsCount'] = ServiceRequest::where('requester_id', $user->id)
            ->whereIn('status', [RequestStatus::OPEN, RequestStatus::IN_PROGRESS])
            ->count();

        // Card: Fila Pendente (das minhas áreas)
        if ($user->role === 'admin' || in_array($user->role, ['manager', 'staff'])) {
            $user->loadMissing('teams.area');
            $userAreaIds = $user->teams->pluck('area_id')->filter()->unique()->all();
            
            $stats['pendingAreaRequestsCount'] = ServiceRequest::whereIn('area_id', $userAreaIds)
                ->where('status', RequestStatus::OPEN)
                ->whereDoesntHave('assignees') 
                ->count();
        }

        // --- 3. STATS DE GESTÃO (Admin/Manager) ---
        if ($user->role === 'admin' || $user->role === 'manager') {
            // PDVs Inativos
            // $stats['inactivePdvsCount'] = Pdv::where('status', 'inactive')->count(); 
            $stats['inactivePdvsCount'] = 0; // Placeholder
        }

        // --- 4. STATS GLOBAIS (Admin-Only) ---
        if ($user->role === 'admin') {
            $stats['totalOpenRequests'] = ServiceRequest::whereIn('status', [RequestStatus::OPEN, RequestStatus::IN_PROGRESS])
                                                        ->count();
            
            // NOVO: Chamados abertos este mês
            $stats['totalRequestsThisMonth'] = ServiceRequest::whereYear('created_at', Carbon::now()->year)
                                                              ->whereMonth('created_at', Carbon::now()->month)
                                                              ->count();
            
            // NOVO: Total de chamados no sistema
            $stats['totalRequestsAllTime'] = ServiceRequest::count(); 
        }
        
        // --- 5. RETORNAR A VIEW ---
        return view('home', [
            'user'               => $user,
            'stats'              => $stats,
            'myAssignedRequests' => $myAssignedRequests, 
        ]);
    }
}