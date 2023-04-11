<?php

declare(strict_types=1);

namespace TypeNormalizer;

/**
 * @method int normalize(mixed $value)
 */
class IntegerNormalizer extends AbstractNormalizer
{
    public const TYPE = 'integer';

    public function fromNull(): int
    {
        return 0;
    }

    public function fromFloat(float $value): int
    {
        $result = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if ($result !== null) {
            return $result;
        }

        throw $this->throwException($value);
    }

    protected function fromStringFilter(string $value): ?int
    {
        $filter = filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        if ($filter !== null) {
            return $filter;
        }

        if (BoolNormalizer::isStringBool($value)) {
            return $this->fromBool(filter_var($value, FILTER_VALIDATE_BOOLEAN));
        }

        $floatVal = filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        if ($floatVal !== null) {
            return $this->fromFloat($floatVal);
        }


        return $value === '' ? 0 : null;
    }

    public function fromInteger(int $value): int
    {
        return $value;
    }

    public function fromBool(bool $value): int
    {
        return $value ? 1 : 0;
    }
}
