<?php

declare(strict_types=1);

namespace TypeNormalizer;

class Utils
{
    public static function classBasename(string|object $class): string
    {
        $class = is_object($class) ? $class::class : $class;

        return basename(str_replace('\\', '/', $class));
    }
}
