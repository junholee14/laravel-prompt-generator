<?php

namespace VsolutionDev\LaravelPromptGenerator\Support\Parser;

use Illuminate\Routing\Route;

class PromptDoc
{
    public function parse(Route $route)
    {
        $method = new \ReflectionMethod(...explode('@', $route->getAction('uses')));

        $docComment = $method->getDocComment();
        $docComment = str_replace(['/**', '*/', '*'], '', $docComment);
        $docComment = trim($docComment);
        $docComment = explode('@prompt-parse', $docComment);
        $docComment = array_map(fn($item) => trim($item), $docComment);
        array_shift($docComment);

        return $docComment;
    }

    private function makeReflectionMethod(string $uses): \ReflectionMethod
    {
        return new \ReflectionMethod(...explode('::', $uses));
    }
}