<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Fragment extends Model
{
    use HasTranslations;

    public $translatable = ['text'];
    protected $guarded = [];
}
