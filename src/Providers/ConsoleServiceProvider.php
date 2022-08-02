<?php

declare(strict_types=1);

namespace UcanLab\LaravelArchitecture\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use UcanLab\LaravelArchitecture\Console\OnionDomainCommand;
use UcanLab\LaravelArchitecture\Console\OnionApplicationUseCaseInputCommand;
use UcanLab\LaravelArchitecture\Console\OnionApplicationUseCaseCommand;
use UcanLab\LaravelArchitecture\Console\OnionApplicationUseCaseOutputCommand;
use UcanLab\LaravelArchitecture\Console\OnionPresentationCommandCommand;
use UcanLab\LaravelArchitecture\Console\OnionPresentationControllerCommand;
use UcanLab\LaravelArchitecture\Console\OnionPresentationRequestCommand;

final class ConsoleServiceProvider extends ServiceProvider
{
    private array $commands = [
        OnionDomainCommand::class,
        OnionApplicationUseCaseInputCommand::class,
        OnionApplicationUseCaseCommand::class,
        OnionApplicationUseCaseOutputCommand::class,
        OnionPresentationCommandCommand::class,
        OnionPresentationControllerCommand::class,
        OnionPresentationRequestCommand::class,
    ];

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->app->singleton(OnionDomainCommand::class, fn ($app) => new OnionDomainCommand($app->make(Filesystem::class)));
        $this->app->singleton(OnionApplicationUseCaseInputCommand::class, fn ($app) => new OnionApplicationUseCaseInputCommand($app->make(Filesystem::class)));
        $this->app->singleton(OnionApplicationUseCaseCommand::class, fn ($app) => new OnionApplicationUseCaseCommand($app->make(Filesystem::class)));
        $this->app->singleton(OnionApplicationUseCaseOutputCommand::class, fn ($app) => new OnionApplicationUseCaseOutputCommand($app->make(Filesystem::class)));
        $this->app->singleton(OnionPresentationCommandCommand::class, fn ($app) => new OnionPresentationCommandCommand($app->make(Filesystem::class)));
        $this->app->singleton(OnionPresentationControllerCommand::class, fn ($app) => new OnionPresentationControllerCommand($app->make(Filesystem::class)));
        $this->app->singleton(OnionPresentationRequestCommand::class, fn ($app) => new OnionPresentationRequestCommand($app->make(Filesystem::class)));

        $this->commands($this->commands);

        $this->publishes([
            __DIR__ . '/config/architecture.php' => config_path('architecture.php'),
        ], 'laravel-architecture');
    }

    /**
     * @return array|string[]
     */
    public function provides(): array
    {
        return $this->commands;
    }
}
