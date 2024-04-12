<?php

namespace Junholee14\LaravelPromptGenerator;

use Junholee14\LaravelPromptGenerator\Support\Parser\PromptDoc;
use Junholee14\LaravelPromptGenerator\Support\Parser\RouteInfo;

class Parser
{
    public function __construct(
        private RouteInfo $routeParser,
        private PromptDoc $promptDoc
    ) {

    }

    public function parseSourceCodes(string $method, string $uri)
    {
        $route = $this->routeParser->parse($method, $uri);
        $promptDoc = $this->promptDoc->parse($route);

        $sourceCodes = [
            'route' => $route->getAction('controller')
        ];
        foreach ($promptDoc as $docComment) {
            $reflectionMethod = new \ReflectionMethod(...explode('::', $docComment));
            $filename = $reflectionMethod->getFileName();
            $startLine = $reflectionMethod->getStartLine() - 1;
            $endLine = $reflectionMethod->getEndLine();
            $length = $endLine - $startLine;

            $source = file($filename);
            $methodSourceCode = implode("", array_slice($source, $startLine, $length));

            $sourceCodes[] = [
                'filename' => $filename,
                'sourceCode' => $methodSourceCode
            ];
        }

        return $sourceCodes;
    }
}
