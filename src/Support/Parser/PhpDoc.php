<?php

namespace Junholee14\LaravelPromptGenerator\Support\Parser;

use Illuminate\Routing\Route;

class PhpDoc
{
    public function parse(string $class, string $methodName)
    {
        $method = new \ReflectionMethod($class, $methodName);

        $docComment = $method->getDocComment();
        $docComment = str_replace(['/**', '*/', '*'], '', $docComment);
        $docComment = trim($docComment);
        $docComment = explode(PHP_EOL, $docComment);
        $docComment = array_filter($docComment, fn($item) => strpos($item, '@see') !== false);
        $docComment = array_map(function($item) {
                $item = str_replace('@see', '', $item);
                return trim($item);
        }, $docComment);

        return $docComment;
    }
}
