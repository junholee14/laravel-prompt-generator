<?php

use function Pest\Laravel\{artisan};

it('generate prompt', function () {
    $filePath = 'prompt_test.md';
    $class = "Junholee14\\\LaravelPromptGenerator\\\Tests\\\Dummy\\\DummyEntry";
    $method = 'parsePrompt';

    artisan("laravel-prompt-generator:gen $class $method --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists($filePath))->toBeTrue();
});

it('generate prompt with other comments', function () {
    $filePath = 'prompt_test.md';
    $class = "Junholee14\\\LaravelPromptGenerator\\\Tests\\\Dummy\\\DummyEntry";
    $method = 'parsePromptWithOtherComments';

    artisan("laravel-prompt-generator:gen $class $method --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists($filePath))->toBeTrue();
});

it('does not print error without phpdoc comments', function () {
    $filePath = 'prompt_test.md';
    $class = "Junholee14\\\LaravelPromptGenerator\\\Tests\\\Dummy\\\DummyEntry";
    $method = 'empty';

    artisan("laravel-prompt-generator:gen $class $method --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists($filePath))->toBeTrue();
});

it('generate prompt without file path', function () {
    \Carbon\Carbon::setTestNow('2021-01-01 00:00:00');
    $class = "Junholee14\\\LaravelPromptGenerator\\\Tests\\\Dummy\\\DummyEntry";
    $method = 'parsePrompt';

    artisan("laravel-prompt-generator:gen $class $method")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    $now = now()->format('Y-m-d_H:i:s');
    $filaName = "prompt_{$now}.md";
    expect(file_exists($filaName))->toBeTrue();
});

it('prints error message when the file path is not a md file', function () {
    $filePath = 'prompt_test.txt';
    $class = "Junholee14\\\LaravelPromptGenerator\\\Tests\\\Dummy\\\DummyEntry";
    $method = 'parsePrompt';

    artisan("laravel-prompt-generator:gen $class $method --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('The file path must be a md file.');
});

it('generate prompt with class in other dir', function () {
    $filePath = 'prompt_test.md';
    $class = "Junholee14\\\LaravelPromptGenerator\\\Tests\\\Dummy\\\DummyEntry";
    $method = 'parsePromptWithClassInOtherDir';

    artisan("laravel-prompt-generator:gen $class $method --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists($filePath))->toBeTrue();
});

it('generate prompt with not found class', function () {
    $filePath = 'prompt_test.md';
    $class = "Junholee14\\\LaravelPromptGenerator\\\Tests\\\Dummy\\\DummyEntry";
    $method = 'parsePromptWithNotFoundClass';

    artisan("laravel-prompt-generator:gen $class $method --filePath=$filePath")
        ->assertExitCode(0)
        ->expectsOutput('Method not found in doc comment: NotFoundClass::minus in Junholee14\LaravelPromptGenerator\Tests\Dummy\DummyEntry')
        ->expectsOutput('Prompt generated successfully.');

    expect(file_exists($filePath))->toBeTrue();
});