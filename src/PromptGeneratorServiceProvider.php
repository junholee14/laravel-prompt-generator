<?php

namespace VsolutionDev\LaravelPromptGenerator;

use GeneratePrompt;
use Illuminate\Support\ServiceProvider;

class PromptGeneratorServiceProvider extends ServiceProvider
{
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
