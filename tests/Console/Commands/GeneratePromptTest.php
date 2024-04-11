<?php

use function Pest\Laravel\{artisan};

it('generate prompt with specified source codes', function () {
    $filePath = 'prompt_test.json';

    artisan('laravel-prompt-generator:gen GET test --filePath=' . $filePath)
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists($filePath))->toBeTrue();
});

it('generate prompt with specified source codes and without file path', function () {
    $method = 'GET';
    $uri = 'test';
    artisan("laravel-prompt-generator:gen $method $uri")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists("prompt_{$method}_{$uri}.json"))->toBeTrue();
});

it('error occurs with invalid file extension', function () {
    artisan('laravel-prompt-generator:gen GET test --filePath=prompt_test.txt')
        ->assertExitCode(0)
        ->expectsOutput('The file path must be a json file.');
});