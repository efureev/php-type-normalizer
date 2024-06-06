<?php

declare(strict_types=1);

namespace TypeNormalizer;

/**
 * @method bool normalize(mixed $value)
 * @method bool throwException(mixed $value)
 */
class BoolNormalizer extends AbstractNormalizer
{
    public const TYPE = 'boolean';

    public function fromNull(): false
    {
        return false;
    }

    public static function isTrue(mixed $value): bool
    {
        return (new static())->normalize($value) === true;
    }

    public static function isFalse(mixed $value): bool
    {
        return (new static())->normalize($value) === false;
    }

    public function fromFloat(float $value): bool
    {
        $result = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($result !== null) {
            return $result;
        }

        return $this->throwException($value);
    }

    protected function fromStringFilter(string $value): ?bool
    {
        $boolVal = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($boolVal || $value === 'false' || $value === '0' || $value === '') {
            return $boolVal;
        }

        $floatVal = filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
        if ($floatVal !== null) {
            return $this->fromFloat($floatVal);
        }

        return null;
    }

    public function fromInteger(int $value): bool
    {
        $boolVal = $this->intIsTrue($value);

        if ($boolVal || $value === 0) {
            return $boolVal;
        }

        return $this->throwException($value);
    }

    public function fromBool(bool $value): bool
    {
        return $value;
    }

    private function intIsTrue(int $value): bool
    {
        return $value === 1;
    }

    public static function isStringBool(string $value): bool
    {
        return $value === 'true' || $value === 'false';
    }
}
