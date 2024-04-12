<?php

namespace Junholee14\LaravelPromptGenerator\Tests;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/test.php');
    }
}