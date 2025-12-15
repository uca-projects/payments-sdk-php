<?php

namespace Uca\Payments;

use Illuminate\Support\ServiceProvider;
use Uca\Payments\Services\ApiPaymentService;

class PaymentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publicar el archivo de configuración
        $this->publishes([
            __DIR__ . '/../config/uca-payments-sdk.php' => config_path('uca-payments-sdk.php'),
        ], 'uca-payments-sdk-config');
    }

    public function register(): void
    {
        // Mergear configuración por defecto (para que funcione incluso si no se publica)
        $this->mergeConfigFrom(
            __DIR__ . '/../config/uca-payments-sdk.php',
            'uca-payments-sdk'
        );

        $this->app->singleton(ApiPaymentService::class, fn() => new ApiPaymentService());
    }
}
