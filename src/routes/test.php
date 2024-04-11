<?php

\Illuminate\Support\Facades\Route::get(
    "/test", [\Junholee14\LaravelPromptGenerator\Tests\Dummy\DummyEntry::class, 'method']
);
