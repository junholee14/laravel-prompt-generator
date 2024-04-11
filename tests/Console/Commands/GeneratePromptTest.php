<?php

use function Pest\Laravel\{artisan};

it('generate prompt with only @prompt-parse comments', function () {
    $filePath = 'prompt_test.json';
    $method = 'GET';
    $uri = 'test/only-prompt-parse';

    artisan("laravel-prompt-generator:gen $method $uri --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists($filePath))->toBeTrue();
});

it('generate prompt with other comments', function () {
    $filePath = 'prompt_test.json';
    $method = 'GET';
    $uri = 'test/prompt-parse-with-other-comments';

    artisan("laravel-prompt-generator:gen $method $uri --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists($filePath))->toBeTrue();
});

it('does not print error without phpdoc comments', function () {
    $filePath = 'prompt_test.json';
    $method = 'GET';
    $uri = 'test/empty';

    artisan("laravel-prompt-generator:gen $method $uri --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists($filePath))->toBeTrue();
});

it('can use lowercase method', function () {
    $method = 'get';
    $uri = 'test/only-prompt-parse';

    artisan("laravel-prompt-generator:gen $method $uri")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    $uriForFileName = str_replace('/', '_', $uri);
    expect(file_exists("prompt_{$method}_{$uriForFileName}.json"))->toBeTrue();
});

it('generate prompt without file path', function () {
    $method = 'GET';
    $uri = 'test/only-prompt-parse';

    artisan("laravel-prompt-generator:gen $method $uri")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    $uriForFileName = str_replace('/', '_', $uri);
    expect(file_exists("prompt_{$method}_{$uriForFileName}.json"))->toBeTrue();
});

it('prints error message when the file path is not a json file', function () {
    $filePath = 'prompt_test.txt';
    $method = 'GET';
    $uri = 'test/only-prompt-parse';
    artisan("laravel-prompt-generator:gen $method $uri --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('The file path must be a json file.');
});
