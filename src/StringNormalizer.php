<?php

declare(strict_types=1);

namespace TypeNormalizer;

/**
 * @method string normalize(mixed $value)
 */
class StringNormalizer extends AbstractNormalizer
{
    public const TYPE = 'string';

    public function fromNull(): string
    {
        return '';
    }

    public function fromString(string $value): mixed
    {
        return $value;
    }

    protected function fromStringFilter(string $value): string
    {
        return '';
    }

    public function fromFloat(float $value): string
    {
        return "$value";
    }

    public function fromInteger(int $value): string
    {
        return "$value";
    }

    public function fromBool(bool $value): string
    {
        return $value === true ? 'true' : 'false';
    }
}
