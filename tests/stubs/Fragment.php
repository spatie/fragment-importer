<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Fragment extends Model
{
    use HasTranslations;

    protected $guarded = [];
    protected $casts = [
        'contains_html' => 'bool',
        'draft'         => 'bool',
        'hidden'        => 'bool',
    ];
    public $translatable = ['text'];
}
