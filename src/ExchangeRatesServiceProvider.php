<?php

namespace Brights\ExchangeRates;

use Brights\ExchangeRates\Contracts\ExchangeRateServiceInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class ExchangeRatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish config file
        $this->publishes([
            __DIR__ . '/../config/exchange-rates.php' => $this->app->configPath('exchange-rates.php'),
        ], 'exchange-rates-config');

        // Publish Migrations
        $this->publishes([
            __DIR__ . '/Database/Migrations' => $this->app->databasePath('migrations'),
        ], 'exchange-rate-migrations');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/exchange-rates.php',
            'exchange-rates'
        );

        // Register the service
        $this->app->bind(ExchangeRateServiceInterface::class, fn() => $this->app->make(Config::get('exchange-rates.default_service')));

        // Register the command
        $this->commands([
            \Brights\ExchangeRates\Console\Commands\MigrateExchangeRatesCommand::class,
        ]);
    }
}
