<?php

namespace App\Providers;

use App\Models\Abonos;
use App\Models\Clientes;
use App\Models\Creditos;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configure application
        $this->configureApp();

        // Registrar filtros globales para rutas
        $this->registerGlobalScopes();

        // Register custom Filament theme
        Filament::serving(function () {
            Filament::registerTheme(
                app(Vite::class)('resources/css/filament.scss'),
            );
        });

        // Register tippy styles
        Filament::registerStyles([
            'https://unpkg.com/tippy.js@6/dist/tippy.css',
        ]);

        // Register scripts
        try {
            Filament::registerScripts([
                app(Vite::class)('resources/js/filament.js'),
            ]);
        } catch (\Exception $e) {
            // Manifest not built yet!
        }

        // Add custom meta (favicon)
        Filament::pushMeta([
            new HtmlString('<link rel="icon"
                                       type="image/x-icon"
                                       href="' . config('app.logo') . '">'),
        ]);

        // Register navigation groups
        Filament::registerNavigationGroups([
            __('Management'),
            __('Referential'),
            __('Security'),
            __('Settings'),
        ]);

        // Force HTTPS over HTTP
        if (env('APP_FORCE_HTTPS') ?? false) {
            URL::forceScheme('https');
        }
    }

    private function configureApp(): void
    {
        try {
            $settings = app(GeneralSettings::class);
            Config::set('app.locale', $settings->site_language ?? config('app.fallback_locale'));
            Config::set('app.name', $settings->site_name ?? env('APP_NAME'));
            Config::set('filament.brand', $settings->site_name ?? env('APP_NAME'));
            Config::set(
                'app.logo',
                $settings->site_logo ? asset('storage/' . $settings->site_logo) : asset('favicon.ico')
            );
            Config::set('filament-breezy.enable_registration', $settings->enable_registration ?? false);
            Config::set('filament-socialite.registration', $settings->enable_registration ?? false);
            Config::set('filament-socialite.enabled', $settings->enable_social_login ?? false);
            Config::set('system.login_form.is_enabled', $settings->enable_login_form ?? false);
            Config::set('services.oidc.is_enabled', $settings->enable_oidc_login ?? false);
        } catch (QueryException $e) {
            // Error: No database configured yet
        }
    }

    /**
     * Registra los global scopes para filtrar por rutas
     */
    private function registerGlobalScopes(): void
    {
        // Solo aplicar en entorno web (no en consola)
        if (!app()->runningInConsole()) {
            // Filtro para clientes
            Clientes::addGlobalScope('ruta_usuario', function ($builder) {
                $user = Auth::user();

                if ($user && !$user->hasRole('admin')) {
                    $builder->whereHas('ruta', function($query) use ($user) {
                        $query->whereHas('usuarios', function($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                    });
                }
            });

            // Filtro para créditos
            Creditos::addGlobalScope('ruta_usuario', function ($builder) {
                $user = Auth::user();

                if ($user && !$user->hasRole('admin')) {
                    $builder->whereHas('ruta', function($query) use ($user) {
                        $query->whereHas('usuarios', function($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                    });
                }
            });

            // Filtro para abonos (opcional)
            Abonos::addGlobalScope('ruta_usuario', function ($builder) {
                $user = Auth::user();

                if ($user && !$user->hasRole('admin')) {
                    $builder->whereHas('credito.ruta', function($query) use ($user) {
                        $query->whereHas('usuarios', function($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                    });
                }
            });
        }
    }
}
