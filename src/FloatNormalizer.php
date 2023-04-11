<?php

declare(strict_types=1);

namespace TypeNormalizer;

/**
 * @method float normalize(mixed $value)
 */
class FloatNormalizer extends AbstractNormalizer
{
    public const TYPE = 'float';

    public function fromNull(): float
    {
        return 0;
    }

    protected function fromStringFilter(string $value): ?float
    {
        $filter = filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        if ($filter !== null) {
            return $filter;
        }

        if (BoolNormalizer::isStringBool($value)) {
            return $this->fromBool(filter_var($value, FILTER_VALIDATE_BOOLEAN));
        }

        return $value === '' ? 0 : null;
    }

    public function fromFloat(float $value): float
    {
        return $value;
    }

    public function fromInteger(int $value): float
    {
        return $value;
    }

    public function fromBool(bool $value): int
    {
        return $value ? 1 : 0;
    }
}
