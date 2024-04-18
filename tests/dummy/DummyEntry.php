<?php

namespace Junholee14\LaravelPromptGenerator\Tests\Dummy;

use Illuminate\Http\JsonResponse;
use Junholee14\LaravelPromptGenerator\Tests\Dummy\NestedDummy\DummyClass3;

class DummyEntry
{
    /**
     * @see DummyClass1::plus
     * @see DummyClass2::minus
     */
    public function parsePrompt(): JsonResponse
    {
        return response()->json();
    }

    /**
     * @see DummyClass1::plus
     * @see DummyClass2::minus
     *
     * @param int $a
     * @param string $b
     * @return JsonResponse
     */
    public function parsePromptWithOtherComments(int $a, string $b): JsonResponse
    {
        return response()->json();
    }

    /**
     * @see DummyClass1::plus
     * @see DummyClass2::minus
     * @see DummyClass3::plus
     */
    public function parsePromptWithClassInOtherDir(): JsonResponse
    {
        return response()->json();
    }

    /**
     * @see DummyClass1::plus
     * @see DummyClass2::minus
     * @see DummyClass3::plus
     * @see NotFoundClass::minus
     */
    public function parsePromptWithNotFoundClass(): JsonResponse
    {
        return response()->json();
    }

    public function empty(): JsonResponse
    {
        return response()->json();
    }
}
