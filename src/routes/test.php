<?php

use Illuminate\Support\Facades\Route;
use Junholee14\LaravelPromptGenerator\Tests\Dummy\DummyEntry;

Route::get("/test/only-prompt-parse", [DummyEntry::class, 'onlyPromptParse']);
Route::get("/test/prompt-parse-with-other-comments", [DummyEntry::class, 'promptParseWithOtherComments']);
Route::get("/test/empty", [DummyEntry::class, 'empty']);
