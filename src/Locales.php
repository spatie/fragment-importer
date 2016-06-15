<?php

namespace Spatie\FragmentImporter;

use Illuminate\Support\Collection;

class Locales
{
    public static function forFragments(): Collection
    {
        return collect()
            ->merge(config('app.locales'))
            ->merge(config('app.backLocales'))
            ->unique();
    }
}
