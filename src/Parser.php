<?php

namespace Junholee14\LaravelPromptGenerator;

use Junholee14\LaravelPromptGenerator\Support\Parser\Ast;
use Junholee14\LaravelPromptGenerator\Support\Parser\PhpDoc;
use Junholee14\LaravelPromptGenerator\Support\Parser\RouteInfo;

class Parser
{
    public function __construct(
        private PhpDoc $promptDoc,
        private Ast $ast
    ) {

    }

    public function parseSourceCodes(string $class, string $method)
    {
        $logs = [];
        $result = [];

        $promptDoc = $this->promptDoc->parse($class, $method);
        $reflectionOfCallerMethod = new \ReflectionMethod($class, $method);
        $reflectionOfCallerClass = new \ReflectionClass($reflectionOfCallerMethod->class);
        $result[] = $this->extract($reflectionOfCallerMethod, $reflectionOfCallerClass);

        foreach ($promptDoc as $docComment) {
            // match class from doc comment
            [$class, $method] = explode('::', $docComment);
            $reflectionClass = $this->ast->matchClassOfPhpdocInAst($class, $reflectionOfCallerClass);
            if (
                empty($method)
                || empty($reflectionClass)
            ) {
                $logs[] = "Method not found in doc comment: {$docComment} in {$reflectionOfCallerMethod->class}";
                continue;
            }
            $reflectionMethod = $reflectionClass->getMethod($method);
            $result[] = $this->extract($reflectionMethod, $reflectionClass);
        }
        $result['logs'] = $logs;

        return $result;
    }

    private function extract(\ReflectionMethod $reflectionMethod, \ReflectionClass $reflectionClass)
    {
        $filename = $reflectionMethod->getFileName();
        $startLine = $reflectionMethod->getStartLine() - 1;
        $endLine = $reflectionMethod->getEndLine();
        $length = $endLine - $startLine;

        $source = file($filename);
        $methodSourceCode = implode("", array_slice($source, $startLine, $length));
        $fileComment = $reflectionClass->getDocComment();
        $docComment = $reflectionMethod->getDocComment();

        return [
            'filename' => $filename,
            'sourceCode' => $methodSourceCode,
            'fileComment' => $fileComment ?: '',
            'methodComment' => $docComment ?: '',
        ];
    }
}
