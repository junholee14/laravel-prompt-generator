<?php

namespace Junholee14\LaravelPromptGenerator\Support\Prompt;

class DefaultPromptMaker extends BasePromptMaker
{
    protected const prompt = <<<PROMPT
Examine the source codes and elucidate the functionality.
{source_codes}
PROMPT;

    public function makePrompt(string $sourceCodes, array $variables = [])
    {
        $prompt = str_replace(
            ['{source_codes}'],
            [$sourceCodes],
            static::prompt
        );

        if (! empty($variables)) {
            foreach ($variables as $index => $variable) {
                $prompt = str_replace(
                    ['{variable' . ($index + 1) . '}'],
                    [$variable],
                    $prompt
                );
            }
        }

        return $prompt;
    }
}