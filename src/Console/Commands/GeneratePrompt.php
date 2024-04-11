<?php

namespace VsolutionDev\LaravelPromptGenerator\Console\Commands;

use Illuminate\Console\Command;
use VsolutionDev\LaravelPromptGenerator\Parser;

class GeneratePrompt extends Command
{
    protected $signature = 'laravel-prompt-generator:gen
        {method : The http method of the route}
        {uri : The uri of the route}
    ';

    protected $description = 'Generate prompt from route';

    public function handle(Parser $parser): void
    {
        $method = $this->argument('method');
        $uri = $this->argument('uri');

        $sourceCodes = $parser->parseSourceCodes($method, $uri);
    }
}
