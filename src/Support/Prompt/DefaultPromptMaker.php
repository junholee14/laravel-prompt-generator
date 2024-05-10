<?php

namespace Junholee14\LaravelPromptGenerator\Support\Prompt;

class DefaultPromptMaker extends BasePromptMaker
{
    protected const prompt = <<<PROMPT
Examine the source codes and elucidate the functionality.
{source_codes}
PROMPT;

    public function makePrompt(string $sourceCodes)
    {
        return str_replace(
            ['{source_codes}'],
            [$sourceCodes],
            self::prompt
        );
    }
}