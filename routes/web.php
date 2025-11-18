<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\PdvController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExternalIdController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\ContractController;
use App\Http\Controllers\MonthlySaleController;
use App\Http\Controllers\FeeInstallmentController;
use App\Http\Controllers\ActivationFeeController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConfiguracaoController;
use App\Http\Controllers\Migration\ClienteMigracaoController;
use App\Http\Controllers\CondominiumController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Settings\PdvSettingsController;
use App\Http\Controllers\Settings\PdvStatusController;
use App\Http\Controllers\Settings\PdvTypeController;

Route::middleware('guest')->group(function () {
    Route::get('/', fn () => redirect()->route('login'));
});

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('clientes')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/criar', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{client}', [ClientController::class, 'show'])->name('show');
        Route::get('/{client}/editar', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{client}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{client}', [ClientController::class, 'destroy'])->name('destroy');

        Route::post('/{client}/pdvs', [ClientController::class, 'attachPdv'])->name('pdvs.attach');
        Route::delete('/{client}/pdvs/{pdv}', [ClientController::class, 'detachPdv'])->name('pdvs.detach');
       
        Route::post('{client}/activation-fee', [ActivationFeeController::class, 'store'])->name('activation-fee.store');
        Route::put('{client}/activation-fee', [ActivationFeeController::class, 'update'])->name('activation-fee.update');
        Route::delete('{client}/activation-fee', [ActivationFeeController::class, 'destroy'])->name('activation-fee.destroy');       
        Route::post('{client}/activation-fee/installments', [ActivationFeeController::class, 'renegotiate'])->name('activation-fee.installments.store');
        Route::delete('{client}/activation-fee/installments/{installment}', [ActivationFeeController::class, 'destroyFeeInstallment'])->name('activation-fee.installments.destroy');

        Route::post('{client}/contracts', [ContractController::class, 'store'])->name('contracts.store');

        Route::prefix('{client}/contacts')->name('contacts.')->group(function () {
            Route::post('/', [ContactController::class, 'store'])->name('store');
        });
    });

    Route::prefix('contacts/{contact}')->name('contacts.')->group(function () {
            Route::put('/', [ContactController::class, 'update'])->name('update');
            Route::delete('/', [ContactController::class, 'destroy'])->name('destroy');
        });

    Route::prefix('pontos-de-venda')->name('pdvs.')->group(function () {
        Route::get('/', [PdvController::class, 'index'])->name('index');
        Route::get('/criar', [PdvController::class, 'create'])->name('create');
        Route::post('/', [PdvController::class, 'store'])->name('store');
        Route::get('/{pdv}', [PdvController::class, 'show'])->name('show');
        Route::get('/{pdv}/editar', [PdvController::class, 'edit'])->name('edit');
        Route::put('/{pdv}', [PdvController::class, 'update'])->name('update');
        Route::delete('/{pdv}', [PdvController::class, 'destroy'])->name('destroy');

        Route::post('/{pdv}/media', [PdvController::class, 'addMedia'])->name('media.store');
        Route::delete('/{pdv}/media/{type}/{index}', [PdvController::class, 'destroyMedia'])->name('media.destroy');

        Route::prefix('{pdv}/equipamentos')->name('equipments.')->group(function () {
            Route::post('/', [PdvController::class, 'attachEquipment'])->name('attach');
            Route::delete('/{equipment}', [PdvController::class, 'detachEquipment'])->name('detach');
        });
    });

    Route::prefix('settings/pdv')->name('settings.pdv.')->group(function () {
        Route::get('/', [PdvSettingsController::class, 'index'])->name('index');
        Route::resource('statuses', PdvStatusController::class);
        Route::resource('types', PdvTypeController::class);
    });

    Route::prefix('equipamentos')->name('equipments.')->group(function () {
        Route::get('/', [EquipmentController::class, 'index'])->name('index');
        Route::get('/criar', [EquipmentController::class, 'create'])->name('create');
        Route::post('/', [EquipmentController::class, 'store'])->name('store');
        Route::get('/{equipment}', [EquipmentController::class, 'show'])->name('show');
        Route::get('/{equipment}/editar', [EquipmentController::class, 'edit'])->name('edit');
        Route::put('/{equipment}', [EquipmentController::class, 'update'])->name('update');
        Route::delete('/{equipment}', [EquipmentController::class, 'destroy'])->name('destroy');

        Route::post('/{equipment}/media', [EquipmentController::class, 'storeMedia'])->name('media.store');
        Route::delete('/{equipment}/media/{type}/{index}', [EquipmentController::class, 'destroyMedia'])->name('media.destroy');
    });

    Route::resource('external-ids', ExternalIdController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::prefix('chamados')->name('requests.')->group(function () {
        Route::get('/', [RequestController::class, 'index'])->name('index');
        Route::get('/criar', [RequestController::class, 'create'])->name('create');
        Route::post('/', [RequestController::class, 'store'])->name('store');
        Route::get('/{request}', [RequestController::class, 'show'])->name('show');
        Route::get('/{request}/editar', [RequestController::class, 'edit'])->name('edit');
        Route::put('/{request}', [RequestController::class, 'update'])->name('update');
        Route::delete('/{request}', [RequestController::class, 'destroy'])->name('destroy');

        Route::prefix('/{request}/assignees')->name('assignees.')->group(function () {
            Route::post('/', [RequestController::class, 'assignUsers'])->name('attach');
            Route::delete('/{user}', [RequestController::class, 'unassignUser'])->name('detach');
        });
    });

    Route::prefix('areas')->name('areas.')->group(function () {
        Route::get('/', [AreaController::class, 'index'])->name('index');
        Route::get('/criar', [AreaController::class, 'create'])->name('create');
        Route::post('/', [AreaController::class, 'store'])->name('store');
        Route::get('/{area}', [AreaController::class, 'show'])->name('show');
        Route::get('/{area}/editar', [AreaController::class, 'edit'])->name('edit');
        Route::put('/{area}', [AreaController::class, 'update'])->name('update');
        Route::delete('/{area}', [AreaController::class, 'destroy'])->name('destroy');

        Route::prefix('/{area}/teams')->name('teams.')->group(function () {
            Route::post('/', [AreaController::class, 'attachTeams'])->name('attach');
            Route::patch('/{team}', [AreaController::class, 'detachTeam'])->name('detach');
        });
    });

    Route::put('/contracts/{contract}', [ContractController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');

    Route::post('/contracts/{contract}/monthly-sales', [MonthlySaleController::class, 'store'])->name('contracts.monthly-sales.store');
    Route::put('/monthly-sales/{monthlySale}', [MonthlySaleController::class, 'update'])->name('monthly-sales.update');
    Route::delete('/monthly-sales/{monthlySale}', [MonthlySaleController::class, 'destroy'])->name('monthly-sales.destroy');

    Route::patch('/fee-installments/{feeInstallment}/pay', [FeeInstallmentController::class, 'pay'])->name('fee-installments.pay');
    Route::patch('/fee-installments/{feeInstallment}/unpay', [FeeInstallmentController::class, 'unpay'])->name('fee-installments.unpay');
});

Route::middleware(['auth', 'role:admin,manager,staff'])->group(function () {
    Route::get('/configuracoes', [ConfiguracaoController::class, 'index'])->name('configuracoes.index');

    Route::prefix('migracao')->name('migracao.')->group(function () {
        Route::get('/clientes', [ClienteMigracaoController::class, 'index'])->name('clientes.index');
        Route::post('/clientes/sincronizar', [ClienteMigracaoController::class, 'syncClients'])->name('clientes.sync');
    });
});

Route::middleware(['auth', 'role:admin,manager'])->prefix('management')->name('management.')->group(function () {
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/criar', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/editar', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

        Route::prefix('{user}/teams')->name('teams.')->group(function () {
            Route::post('/', [UserController::class, 'attachTeams'])->name('attach');
            Route::delete('/{team}', [UserController::class, 'detachTeam'])->name('detach');
        });
    });

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
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::post('condominiums/{condominium}/contacts', [CondominiumController::class, 'storeContact'])->name('condominiums.contacts.store');
    Route::delete('condominiums/{condominium}/contacts/{contact}', [CondominiumController::class, 'destroyContact'])->name('condominiums.contacts.destroy');
});

require __DIR__.'/auth.php';
