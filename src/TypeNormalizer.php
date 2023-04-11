<?php

declare(strict_types=1);

namespace TypeNormalizer;

class TypeNormalizer
{
    /**
     * @param callable|array{callable, mixed} ...$mw
     */
    public static function toInt(mixed $value, callable|array ...$mw): int
    {
        return (new IntegerNormalizer())->withMiddleware(...$mw)->normalize($value);
    }

    /**
     * @param callable|array{callable, mixed} ...$mw
     */
    public static function toString(mixed $value, callable|array ...$mw): string
    {
        return (new StringNormalizer())->withMiddleware(...$mw)->normalize($value);
    }

    /**
     * @param callable|array{callable, mixed} ...$mw
     */
    public static function toBool(mixed $value, callable|array ...$mw): bool
    {
        return (new BoolNormalizer())->withMiddleware(...$mw)->normalize($value);
    }

    /**
     * @param callable|array{callable, mixed} ...$mw
     */
    public static function toFloat(mixed $value, callable|array ...$mw): float
    {
        return (new FloatNormalizer())->withMiddleware(...$mw)->normalize($value);
    }
}
