<?php

namespace Junholee14\LaravelPromptGenerator\Support\Prompt;

abstract class BasePromptMaker
{
    protected const prompt = '';

    abstract public function makePrompt(string $sourceCodes, array $variables = []);
}