<?php

namespace Junholee14\LaravelPromptGenerator\Support\Parser;

use Illuminate\Routing\Route;

class PromptDoc
{
    public function parse(Route $route)
    {
        $method = new \ReflectionMethod(...explode('@', $route->getAction('uses')));

        $docComment = $method->getDocComment();
        $docComment = str_replace(['/**', '*/', '*'], '', $docComment);
        $docComment = trim($docComment);
        $docComment = explode(PHP_EOL, $docComment);
        $docComment = array_filter($docComment, fn($item) => strpos($item, '@prompt-parse') !== false);
        $docComment = array_map(function($item) {
                $item = str_replace('@prompt-parse', '', $item);
                return trim($item);
        }, $docComment);

        return $docComment;
    }
}
