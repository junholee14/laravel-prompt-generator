<?php

return [
    'prompt' => [
        'content' => "Examine the source codes and elucidate the functionality within the designated language.
    The target audience is {target}, and customize the explanation to suit their level of technical expertise.
    language: {language}
    
    {source_codes}",
        'language' => env("LARAVEL_PROMPT_GENERATOR_LANGUAGE", "English"),
    ],
];
