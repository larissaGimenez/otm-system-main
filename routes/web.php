<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\Migration\ClienteMigracaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CondominiumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExternalIdController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\MonthlySaleController;

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
        
        // Rotas CRUD padrão
        Route::get('/', [RequestController::class, 'index'])->name('index');
        Route::get('/criar', [RequestController::class, 'create'])->name('create');
        Route::post('/', [RequestController::class, 'store'])->name('store');
        Route::get('/{request}', [RequestController::class, 'show'])->name('show');
        Route::get('/{request}/editar', [RequestController::class, 'edit'])->name('edit');
        Route::put('/{request}', [RequestController::class, 'update'])->name('update');
        Route::delete('/{request}', [RequestController::class, 'destroy'])->name('destroy');

        // Rotas para gerenciar responsáveis (Assignees)
        // Usamos um subgrupo para organizar e nomear
        Route::prefix('/{request}/assignees')->name('assignees.')->group(function () {
            
            // Rota para adicionar um ou mais responsáveis
            Route::post('/', [RequestController::class, 'assignUsers'])->name('attach');
            
            // Rota para remover um responsável específico
            Route::delete('/{user}', [RequestController::class, 'unassignUser'])->name('detach');
        
        });
        
    });

    // CRUD de Pontos de Venda (PDV)
    Route::prefix('pontos-de-venda')->name('pdvs.')->group(function () {
        Route::get('/', [PdvController::class, 'index'])->name('index');
        Route::get('/criar', [PdvController::class, 'create'])->name('create');
        Route::post('/', [PdvController::class, 'store'])->name('store');
        Route::get('/{pdv}', [PdvController::class, 'show'])->name('show');
        Route::get('/{pdv}/editar', [PdvController::class, 'edit'])->name('edit');
        Route::put('/{pdv}', [PdvController::class, 'update'])->name('update');
        Route::delete('/{pdv}', [PdvController::class, 'destroy'])->name('destroy');
    });

    Route::post('/pdvs/{pdv}/media', [PdvController::class, 'addMedia'])->name('pdvs.media.store');
    Route::delete('/pdvs/{pdv}/media/{type}/{index}', [PdvController::class, 'destroyMedia'])->name('pdvs.media.destroy');

    // CRUD de Equipamentos
    Route::prefix('equipamentos')->name('equipments.')->group(function () {
        Route::get('/', [EquipmentController::class, 'index'])->name('index');
        Route::get('/criar', [EquipmentController::class, 'create'])->name('create');
        Route::post('/', [EquipmentController::class, 'store'])->name('store');
        Route::get('/{equipment}', [EquipmentController::class, 'show'])->name('show');
        Route::get('/{equipment}/editar', [EquipmentController::class, 'edit'])->name('edit');
        Route::put('/{equipment}', [EquipmentController::class, 'update'])->name('update');
        Route::delete('/{equipment}', [EquipmentController::class, 'destroy'])->name('destroy');
    });

    Route::post('/equipments/{equipment}/photos', [EquipmentController::class, 'addPhotos'])
        ->name('equipments.photos.store');
    Route::delete('/equipments/{equipment}/photos/{index}', [EquipmentController::class, 'removePhoto'])
     ->name('equipments.photos.destroy');

    Route::prefix('pontos-de-venda/{pdv}/equipamentos')->name('pdvs.equipments.')->group(function () {
        Route::post('/', [PdvController::class, 'attachEquipment'])->name('attach');
        Route::delete('/{equipment}', [PdvController::class, 'detachEquipment'])->name('detach');
    });

    Route::resource('external-ids', ExternalIdController::class)
    ->only(['index','store','update','destroy']);

    Route::prefix('areas')->name('areas.')->group(function () {
        Route::get('/', [AreaController::class, 'index'])->name('index');
        Route::get('/criar', [AreaController::class, 'create'])->name('create');
        Route::post('/', [AreaController::class, 'store'])->name('store');
        Route::get('/{area}', [AreaController::class, 'show'])->name('show');
        Route::get('/{area}/editar', [AreaController::class, 'edit'])->name('edit');
        Route::put('/{area}', [AreaController::class, 'update'])->name('update');
        Route::delete('/{area}', [AreaController::class, 'destroy'])->name('destroy');

        // Rotas para associação de Equipes (Teams) a uma Área
        Route::prefix('/{area}/teams')->name('teams.')->group(function () {
            Route::post('/', [AreaController::class, 'attachTeams'])->name('attach');
            Route::patch('/{team}', [AreaController::class, 'detachTeam'])->name('detach');
        });
    });

    // --- ROTAS PARA GERENCIAR CONTRATOS (via Modals) ---
    // Um contrato está sempre ligado a um PDV
    Route::post('/pontos-de-venda/{pdv}/contracts', [ContractController::class, 'store'])
        ->name('pdvs.contracts.store');

    // Um contrato específico pode ser atualizado ou deletado
    Route::put('/contracts/{contract}', [ContractController::class, 'update'])
        ->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])
        ->name('contracts.destroy');


    // --- ROTAS PARA GERENCIAR FATURAMENTO MENSAL (via Modals) ---
    // O faturamento está sempre ligado a um Contrato
    Route::post('/contracts/{contract}/monthly-sales', [MonthlySaleController::class, 'store'])
        ->name('contracts.monthly-sales.store');
        
    // Um faturamento específico pode ser atualizado ou deletado
    Route::put('/monthly-sales/{monthlySale}', [MonthlySaleController::class, 'update'])
        ->name('monthly-sales.update');
    Route::delete('/monthly-sales/{monthlySale}', [MonthlySaleController::class, 'destroy'])
        ->name('monthly-sales.destroy');
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
        Route::post('/{team}/users', [TeamController::class, 'attachUsers'])->name('users.attach');
        Route::delete('/{team}/users/{user}', [TeamController::class, 'removeUser'])->name('users.remove');
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

    Route::resource('condominiums', CondominiumController::class);

    Route::post('condominiums/{condominium}/contacts', [CondominiumController::class, 'storeContact'])
        ->name('condominiums.contacts.store');

    Route::delete('condominiums/{condominium}/contacts/{contact}', [CondominiumController::class, 'destroyContact'])
        ->name('condominiums.contacts.destroy');

});

/*
|--------------------------------------------------------------------------
| Arquivo de Rotas de Autenticação
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';