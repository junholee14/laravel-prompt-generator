<?php

namespace Junholee14\LaravelPromptGenerator\Tests\Dummy;

use Illuminate\Http\JsonResponse;

class DummyEntry
{
    /**
     * @prompt-parse Junholee14\LaravelPromptGenerator\Tests\Dummy\DummyClass1::plus
     * @prompt-parse Junholee14\LaravelPromptGenerator\Tests\Dummy\DummyClass2::minus
     */
    public function onlyPromptParse(): JsonResponse
    {
        return response()->json();
    }

    /**
     * @prompt-parse Junholee14\LaravelPromptGenerator\Tests\Dummy\DummyClass1::plus
     * @prompt-parse Junholee14\LaravelPromptGenerator\Tests\Dummy\DummyClass2::minus
     *
     * @param int $a
     * @param string $b
     * @return JsonResponse
     */
    public function promptParseWithOtherComments(int $a, string $b): JsonResponse
    {
        return response()->json();
    }

    public function empty(): JsonResponse
    {
        return response()->json();
    }
}
