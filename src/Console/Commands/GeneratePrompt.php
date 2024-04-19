<?php

namespace Junholee14\LaravelPromptGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Junholee14\LaravelPromptGenerator\Parser;

class GeneratePrompt extends Command
{
    protected $signature = 'laravel-prompt-generator:gen
        {class : The class name to parse}
        {method : The method to parse}
        {--audience= : The target audience of the prompt (default: developers)}
        {--filePath= : The file path to save the prompt (md format)}
    ';

    protected $description = 'Generate prompt from route';

    public function handle(Parser $parser): void
    {
        if (! $this->validateInputParams()) {
            return;
        }
        $language = config('laravel-prompt-generator.prompt.language');
        $class = $this->argument('class');

        $method = $this->argument('method');
        $targetAudience = $this->option('audience') ?? 'developers';
        $now = now()->format('Y-m-d_H:i:s');
        $filePath = $this->option('filePath') ?? "prompt_{$now}.md";

        $sourceCodes = $parser->parseSourceCodes($class, $method);
        Log::info($sourceCodes['logs']);
        // print logs
        foreach (array_slice($sourceCodes['logs'], 0, 5) as $log) {
            $this->info($log);
        }
        if (
            ! empty($sourceCodes['logs'])
            && count($sourceCodes['logs']) > 5
        ) {
            $this->info("... Check out the rest of the logs in the log file.");
        }

        unset($sourceCodes['logs']);

        $sourceCodes = json_encode(
            [
                'source_codes' => $sourceCodes
            ],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
        File::put(
            $filePath,
            $this->makePrompt($language, $targetAudience, $sourceCodes)
        );

        $this->info("Prompt generated successfully.");
    }

    private function validateInputParams()
    {
        $filePath = $this->option('filePath');

        if (empty($filePath)) {
            return true;
        } elseif (!str_ends_with($filePath, '.md')) {
            $this->error('The file path must be a md file.');
            return false;
        }

        return true;
    }

    private function makePrompt(
        string $language,
        string $targetAudience,
        string $sourceCodes
    ) {
        $prompt = config('laravel-prompt-generator.prompt.content');
        return str_replace(
            ['{target}', '{language}', '{source_codes}'],
            [$targetAudience, $language, $sourceCodes],
            $prompt
        );
    }
}
