<?php

namespace Junholee14\LaravelPromptGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Junholee14\LaravelPromptGenerator\Parser;

class GeneratePrompt extends Command
{
    protected $signature = 'laravel-prompt-generator:gen
        {method : The http method of the route}
        {uri : The uri of the route}
        {--filePath= : The file path to save the prompt (json format)}
    ';

    protected $description = 'Generate prompt from route';

    public function handle(Parser $parser): void
    {
        $method = strtoupper($this->argument('method'));
        $uri = $this->argument('uri');
        $uriForFileName = str_replace('/', '_', $uri);
        $filePath = $this->option('filePath') ?? "prompt_{$method}_{$uriForFileName}.json";

        if (!str_ends_with($filePath, '.json')) {
            $this->error('The file path must be a json file.');
            return;
        }

        $sourceCodes = $parser->parseSourceCodes($method, $uri);
        $result = json_encode(
            $sourceCodes,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
        File::put(
            $filePath,
            $result
        );
        $this->info("Prompt generated successfully.");
    }
}
