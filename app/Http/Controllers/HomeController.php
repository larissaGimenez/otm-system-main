<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Company;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            
        ]);
    }
}