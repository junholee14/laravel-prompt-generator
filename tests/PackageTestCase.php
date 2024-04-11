<?php

namespace Junholee14\LaravelPromptGenerator\Tests;

use Orchestra\Testbench\TestCase;
use Junholee14\LaravelPromptGenerator\PromptGeneratorServiceProvider;

class PackageTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            PromptGeneratorServiceProvider::class,
        ];
    }
}
