<?php

namespace App\Models;

use Spatie\TranslationLoader\LanguageLine;

class Fragment extends LanguageLine
{
    public $casts = [
        'contains_html' => 'boolean',
        'hidden' => 'boolean',
        'text' => 'array',
    ];
}
