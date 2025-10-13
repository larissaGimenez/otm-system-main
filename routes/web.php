<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\Migration\ClienteMigracaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Rotas de Visitantes (Não Autenticados)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
});

/*
|--------------------------------------------------------------------------
| Rotas de Usuários Autenticados
|--------------------------------------------------------------------------
*/

// Rotas para QUALQUER usuário logado
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas para a equipe interna
Route::middleware(['auth', 'role:admin,manager,staff'])->group(function () {
    Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');

    Route::prefix('migracao')->name('migracao.')->group(function () {
        Route::get('/clientes', [ClienteMigracaoController::class, 'index'])->name('clientes.index');
        Route::post('/clientes/sincronizar', [ClienteMigracaoController::class, 'syncClients'])->name('clientes.sync');
    });
});

// Rotas para Gerentes e Administradores
Route::middleware(['auth', 'role:admin,manager'])->prefix('management')->name('management.')->group(function () {
    Route::resource('users', UserController::class);
});

// Rotas exclusivas para Administradores
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', CompanyController::class);
});

/*
|--------------------------------------------------------------------------
| Arquivo de Rotas de Autenticação
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';