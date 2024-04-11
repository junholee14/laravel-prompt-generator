<?php

namespace VsolutionDev\LaravelPromptGenerator;

use Illuminate\Support\ServiceProvider;
use VsolutionDev\LaravelPromptGenerator\Console\Commands\GeneratePrompt;

class PromptGeneratorServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GeneratePrompt::class,
            ]);

            // for test use
            $this->loadRoutesFrom(__DIR__ . '/routes/test.php');
        }
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/laravel-prompt-generator.php' => config_path('laravel-prompt-generator.php')
            ], 'config');
        }

        $this->mergeConfigFrom(__DIR__ . '/config/laravel-prompt-generator.php', 'laravel-prompt-generator');
    }
}
