<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\Migration\ClienteMigracaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\RequestController;

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

    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD de Chamados (Requests)
    Route::prefix('chamados')->name('requests.')->group(function () {
        Route::get('/', [RequestController::class, 'index'])->name('index');
        Route::get('/criar', [RequestController::class, 'create'])->name('create');
        Route::post('/', [RequestController::class, 'store'])->name('store');
        Route::get('/{request}', [RequestController::class, 'show'])->name('show');
        Route::get('/{request}/editar', [RequestController::class, 'edit'])->name('edit');
        Route::put('/{request}', [RequestController::class, 'update'])->name('update');
        Route::delete('/{request}', [RequestController::class, 'destroy'])->name('destroy');
    });
});

// Rotas para a equipe interna (staff, manager, admin)
Route::middleware(['auth', 'role:admin,manager,staff'])->group(function () {
    Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');

    Route::prefix('migracao')->name('migracao.')->group(function () {
        Route::get('/clientes', [ClienteMigracaoController::class, 'index'])->name('clientes.index');
        Route::post('/clientes/sincronizar', [ClienteMigracaoController::class, 'syncClients'])->name('clientes.sync');
    });
});

// Rotas para Gerentes e Administradores
Route::middleware(['auth', 'role:admin,manager'])->prefix('management')->name('management.')->group(function () {
    
    // CRUD de Usuários
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/criar', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/editar', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        // MUDANÇA: Grupo aninhado para gerenciar as equipes de um usuário
        Route::prefix('{user}/teams')->name('teams.')->group(function () {
            Route::post('/', [UserController::class, 'attachTeams'])->name('attach');
            Route::delete('/{team}', [UserController::class, 'detachTeam'])->name('detach');
        });
    });

    // CRUD de Equipes (Teams)
    Route::prefix('equipes')->name('teams.')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::get('/criar', [TeamController::class, 'create'])->name('create');
        Route::post('/', [TeamController::class, 'store'])->name('store');
        Route::get('/{team}', [TeamController::class, 'show'])->name('show');
        Route::get('/{team}/editar', [TeamController::class, 'edit'])->name('edit');
        Route::put('/{team}', [TeamController::class, 'update'])->name('update');
        Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');
        Route::post('/{team}/members', [TeamController::class, 'attachUsers'])->name('attachUsers');
        Route::delete('/{team}/members/{user}', [TeamController::class, 'removeUser'])->name('removeUser');
    });

    // CRUD de Pontos de Venda (PDV)
    Route::prefix('pontos-de-venda')->name('pdv.')->group(function () {
        Route::get('/', [PdvController::class, 'index'])->name('index');
        Route::get('/criar', [PdvController::class, 'create'])->name('create');
        Route::post('/', [PdvController::class, 'store'])->name('store');
        Route::get('/{pdv}', [PdvController::class, 'show'])->name('show');
        Route::get('/{pdv}/editar', [PdvController::class, 'edit'])->name('edit');
        Route::put('/{pdv}', [PdvController::class, 'update'])->name('update');
        Route::delete('/{pdv}', [PdvController::class, 'destroy'])->name('destroy');
    });

});

// Rotas exclusivas para Administradores
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD de Empresas
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('/criar', [CompanyController::class, 'create'])->name('create');
        Route::post('/', [CompanyController::class, 'store'])->name('store');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('show');
        Route::get('/{company}/editar', [CompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Arquivo de Rotas de Autenticação
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';