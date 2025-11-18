<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PdvSettingsController extends Controller
{
    /**
     * Exibe a tela principal de configurações de PDV (Menu).
     */
    public function index(): View
    {
        return view('settings.pdv.index');
    }
}