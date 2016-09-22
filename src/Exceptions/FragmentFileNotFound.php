<?php

namespace Spatie\FragmentImporter\Exceptions;

use Exception;

class FragmentFileNotFound extends Exception
{
    public static function inPath(string $path)
    {
        return new static("Fragment import file not found in {$path}");
    }
}
