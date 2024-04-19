<?php

namespace Junholee14\LaravelPromptGenerator\Support\Parser;

use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\ParserFactory;

class Ast
{
    public function matchClassOfPhpdocInAst(string $needleClass, \ReflectionClass $callerClass): ?\ReflectionClass
    {
        if (class_exists($needleClass)) {
            return new \ReflectionClass($needleClass);
        } else {
            return $this->lookForClassInAst($needleClass, $callerClass);
        }
    }

    private function lookForClassInAst(string $needleClass, \ReflectionClass $callerClass): ?\ReflectionClass
    {
        $namespace = $callerClass->getNamespaceName();
        $class = $namespace . '\\' . $needleClass;
        if (class_exists($class)) {
            return new \ReflectionClass($class);
        }

        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $ast = $parser->parse(file_get_contents($callerClass->getFileName()));

        return $this->getMatchedClassInUseFromAst($needleClass, $ast);
    }

    private function getMatchedClassInUseFromAst(string $needleClass, array $ast): ?\ReflectionClass
    {
        foreach ($ast as $node) {
            if ($node instanceof Namespace_) {
                foreach ($node->stmts as $stmt) {
                    if ($stmt instanceof Use_) {
                        foreach ($stmt->uses as $use) {
                            $alias = $use->alias ? $use->alias->name : $use->name->getLast();
                            if ($alias === $needleClass) {
                                return new \ReflectionClass($use->name->toCodeString());
                            }
                        }
                    }
                }
            }
        }

        return null;
    }
}
