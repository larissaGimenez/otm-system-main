<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class EquipmentsSettingsController extends Controller
{
    /**
     * Exibe a tela principal de configurações de PDV (Menu).
     */
    public function index(): View
    {
        return view('settings.equipments.index');
    }
}