<?php

namespace VsolutionDev\LaravelPromptGenerator\Tests;

use Orchestra\Testbench\TestCase;
use VsolutionDev\LaravelPromptGenerator\PromptGeneratorServiceProvider;

class PackageTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            PromptGeneratorServiceProvider::class,
        ];
    }
}
