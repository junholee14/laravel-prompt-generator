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
        {--audience= : The target audience of the prompt (default: developers)}
        {--filePath= : The file path to save the prompt (md format)}
    ';

    protected $description = 'Generate prompt from route';

    private const PROMPT = "
    Examine the API source codes and elucidate the functionality within the designated language.
    The target audience is {target}, and customize the explanation to suit their level of technical expertise.
    language: {language}
    
    {api_info}
    ";

    public function handle(Parser $parser): void
    {
        $language = config('laravel-prompt-generator.prompt.language');
        $method = strtoupper($this->argument('method'));
        $uri = $this->argument('uri');
        $uriForFileName = str_replace('/', '_', $uri);
        $targetAudience = $this->option('audience') ?? 'developers';
        $filePath = $this->option('filePath') ?? "prompt_{$method}_{$uriForFileName}.md";

        if (!str_ends_with($filePath, '.md')) {
            $this->error('The file path must be a md file.');
            return;
        }

        $sourceCodes = $parser->parseSourceCodes($method, $uri);
        $apiInfo = json_encode(
            [
                'api_uri' => $uri,
                'source_codes' => $sourceCodes
            ],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
        File::put(
            $filePath,
            $this->makePrompt($language, $targetAudience, $apiInfo)
        );

        $this->info("Prompt generated successfully.");
    }

    private function makePrompt(
        string $language,
        string $targetAudience,
        string $apiInfo
    ) {
        return str_replace(
            ['{target}', '{language}', '{api_info}'],
            [$targetAudience, $language, $apiInfo],
            self::PROMPT
        );
    }
}
