<?php

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Http\Controllers\RoadMap\DataController;
use App\Http\Controllers\Auth\OidcAuthController;
use App\Http\Controllers\CreditoController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\YapeClienteController;


// Validate an account
Route::get('/validate-account/{user:creation_token}', function (User $user) {
    return view('validate-account', compact('user'));
})
    ->name('validate-account')
    ->middleware([
        'web',
        DispatchServingFilamentEvent::class
    ]);

// Login default redirection
Route::redirect('/login-redirect', '/login')->name('login');

// Road map JSON data
Route::get('road-map/data/{project}', [DataController::class, 'data'])
    ->middleware(['verified', 'auth'])
    ->name('road-map.data');

Route::name('oidc.')
    ->prefix('oidc')
    ->group(function () {
        Route::get('redirect', [OidcAuthController::class, 'redirect'])->name('redirect');
        Route::get('callback', [OidcAuthController::class, 'callback'])->name('callback');
    });


Route::middleware(['auth', 'check.ruta.access'])->group(function () {
    Route::get('/rutas/{ruta}', [RutaController::class, 'show'])->name('rutas.show');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});

Route::post('/creditos/actualizar', [CreditoController::class, 'actualizarDatosCredito'])->name('creditos.actualizar');

Route::get('/clientes/{id}', [YapeClienteController::class, 'getClienteInfo']);
Route::get('/cobradores-por-ruta/{rutaId}', [YapeClienteController::class, 'getCobradoresPorRuta']);
/*

Route::middleware(['auth'])->group(function () {
    // Rutas comunes

    // Rutas protegidas por política
    Route::middleware(['can:access-ruta,ruta'])->group(function () {
        Route::get('/rutas/{ruta}', [RutaController::class, 'show'])->name('rutas.show');
        Route::get('/rutas/{ruta}/clientes', [RutaController::class, 'clientes'])->name('rutas.clientes');
    });

    // Rutas de gestión
    Route::middleware(['can:manage-rutas'])->group(function () {
        Route::resource('rutas', RutaController::class)->except(['show', 'index']);
    });

    // Rutas de cobros
    Route::middleware(['can:collect-payments', 'can:access-ruta,ruta'])->post('/rutas/{ruta}/abonos', [RutaController::class, 'storePayment']);

    // Rutas de reportes
    Route::middleware(['can:view-ruta-reports'])->group(function () {
        Route::get('/rutas/{ruta}/reportes', [RutaController::class, 'reportes'])->name('rutas.reports');
    });
});
*/
