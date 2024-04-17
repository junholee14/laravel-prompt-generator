<?php

namespace Junholee14\LaravelPromptGenerator\Support\Parser;

use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;

class Ast
{
    public function extractNamespacesAndMethodsFromMethod($ast, $methodName) {
        $result = [];

        foreach ($ast as $node) {
            if ($node instanceof Namespace_) {
                 $namespace = $node->name->toString();

                $class = $this->findClassInNamespace($node);
                if ($class) {
                    $method = $this->findMethodInClass($class, $methodName);
                    if ($method) {
                        $result = array_merge($result, $this->extractMethodCallsFromMethod($namespace, $class, $method));
                    }
                }
            }
        }

        return $result;
    }

    private function findClassInNamespace($namespace) {
        foreach ($namespace->stmts as $stmt) {
            if ($stmt instanceof Class_) {
                return $stmt;
            }
        }
        return null;
    }

    private function findMethodInClass($class, $methodName) {
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && $stmt->name->name === $methodName) {
                return $stmt;
            }
        }
        return null;
    }

    private function extractMethodCallsFromMethod($namespace, $class, $method) {
        $result = [];

        foreach ($method->stmts as $stmt) {
            if ($stmt instanceof Expression &&
                $stmt->expr instanceof Assign &&
                $stmt->expr->expr instanceof MethodCall) {

                $methodCall = $stmt->expr->expr;

                if ($methodCall->var instanceof PropertyFetch) {
                    $propertyName = $methodCall->var->name->name;
                    $calledMethodName = $methodCall->name->name;

                    $paramType = $this->findParamTypeInConstructor($class, $propertyName);
                    if ($paramType) {
                        $result[] = [
                            'namespace' => $namespace . '\\' . $paramType,
                            'method' => $calledMethodName
                        ];
                    }
                }
            }
        }

        return $result;
    }

    private function findParamTypeInConstructor($class, $propertyName) {
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && $stmt->name->name === '__construct') {
                foreach ($stmt->params as $param) {
                    if ($param->var->name === $propertyName) {
                        return $param->type->name;
                    }
                }
                break;
            }
        }
        return null;
    }

    /**
     * 생성자에서 초기화 되지않고 메소드에서 초기화 되는 경우.. (WIP)
     *
     * @param $methodName
     * @param $ast
     * @return array
     */
    private function parseMethodCalls($methodName, $ast) {
        foreach ($ast as $node) {
            if ($node instanceof ClassMethod && $node->name->name === $methodName) {
                $methodCalls = [];

                foreach ($node->stmts as $stmt) {
                    if ($stmt instanceof Expression) {
                        $expr = $stmt->expr;

                        if ($expr instanceof Assign) {
                            $expr = $expr->expr;
                        }

                        if ($expr instanceof MethodCall) {
                            $var = $expr->var;

                            if ($var instanceof New_) {
                                if (is_string($var->class->name)) {
                                    $namespace = 'Junholee14\\LaravelPromptGenerator\\Tests\\Dummy' . '\\' . $var->class->name;
                                } else {
                                    $namespace = implode('\\', $var->class->name);
                                }
                                $methodCall = $expr->name->name;
                                $methodCalls[] = ['namespace' => $namespace, 'method' => $methodCall];
                            } elseif ($var instanceof PropertyFetch) {
                                $propertyName = $var->name->name;
                                $methodCall = $expr->name->name;
                                $methodCalls[] = ['property' => $propertyName, 'method' => $methodCall];
                            }
                        }
                    }
                }

                return $methodCalls;
            }

            if (isset($node->stmts) && is_array($node->stmts)) {
                $result = self::parseMethodCalls($methodName, $node->stmts);

                if (!empty($result)) {
                    return $result;
                }
            }
        }

        return [];
    }
}