<?php

namespace Uca\Payments;

use Illuminate\Support\ServiceProvider;

class PaymentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Registrar vistas del paquete
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'uca-payments');

        // Publicar el archivo de configuración
        $this->publishes([
            __DIR__ . '/../config/uca-payments-sdk.php' => config_path('uca-payments-sdk.php'),
        ], 'uca-payments-sdk-config');

        // Permitir publicar vistas
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/uca-payments'),
        ], 'uca-payments-sdk-views');
    }

    public function register(): void
    {
        // Mergear configuración por defecto (para que funcione incluso si no se publica)
        $this->mergeConfigFrom(
            __DIR__ . '/../config/uca-payments-sdk.php',
            'uca-payments-sdk'
        );
    }
}
