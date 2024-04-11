<?php

\Illuminate\Support\Facades\Route::get(
    "/test", [\VsolutionDev\LaravelPromptGenerator\Tests\Dummy\DummyEntry::class, 'method']
);
