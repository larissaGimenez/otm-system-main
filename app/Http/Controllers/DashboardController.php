<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Apenas exibe a view principal do dashboard.
     */
    public function index(): View
    {
        return view('dashboard');
    }
}