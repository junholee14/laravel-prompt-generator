<?php

namespace Junholee14\LaravelPromptGenerator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Junholee14\LaravelPromptGenerator\Parser;
use Junholee14\LaravelPromptGenerator\Support\Prompt\BasePromptMaker;

class GeneratePrompt extends Command
{
    protected $signature = 'laravel-prompt-generator:gen
        {class : The class name to parse}
        {method : The method to parse}
        {variables?* : The prompt variable(s) to use}
        {--template= : The prompt template to use}
        {--filePath= : The file path to save the prompt (md format)}
    ';

    protected $description = 'Generate prompt from method';

    public function handle(Parser $parser): void
    {
        $now = now()->format('Y-m-d_H:i:s');
        $class = $this->argument('class');
        $method = $this->argument('method');
        $variables = $this->argument('variables');
        $template = $this->option('template') ?? 'default';
        $filePath = $this->option('filePath') ?? "prompt_{$now}.md";

        if (! $this->validateFilePath($filePath) && ! $this->validateTemplate($template)) return;

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

        if ($template === 'default') {
            $promptMaker = app(config('laravel-prompt-generator.prompt.default'));
        } else {
            $promptMaker = app(config('laravel-prompt-generator.prompt.templates')[$template]);
        }

        File::put(
            $filePath,
            $promptMaker->makePrompt($sourceCodes, $variables)
        );

        $this->info("Prompt generated successfully.");
    }

    private function validateFilePath($filePath)
    {
        if (! preg_match('/\.md$/', $filePath)) {
            $this->error("The file path must be a md file.");
            return false;
        }

        $directory = dirname($filePath);
        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0777, true, true);
            $this->info("The directory {$directory} is created.");
        }

        return true;
    }

    private function validateTemplate(string $template)
    {
        $promptMaker = null;
        if ($template === 'default') {
            $promptMaker = app(config('laravel-prompt-generator.prompt.default'));
        } else {
            $promptMaker = app(config('laravel-prompt-generator.prompt.templates')[$template]);
        }

        if (empty($promptMaker)) {
            $this->error("The template {$template} is not found.");
            return false;
        }

        if (! $promptMaker instanceof BasePromptMaker) {
            $this->error("The template {$template} is not a valid prompt maker.");
            return false;
        }

        return true;
    }
}
