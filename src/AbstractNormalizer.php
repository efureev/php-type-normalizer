<?php

declare(strict_types=1);

namespace TypeNormalizer;

abstract class AbstractNormalizer
{
    public const TYPE = 'unknown';

    /** @var array<callable|array{callable, mixed}> */
    private array $mw = [];

    private function normalizeValue(mixed $value): mixed
    {
        $normValue = $this->tapValue($value);

        $this->checkForScalar($normValue);

        return $normValue;
    }

    /**
     * @param callable|array{callable, mixed} ...$mw
     */
    public function withMiddleware(callable|array ...$mw): static
    {
        $this->mw = array_merge($this->mw, $mw);

        return $this;
    }

    private function tapValue(mixed $value): mixed
    {
        return is_callable($value) && ($value instanceof \Closure || (is_string($value) && class_exists($value)))
            ? $value()
            : $value;
    }

    public function normalize(mixed $value): mixed
    {
        $normValue = $this->normalizeValue($value);

        $resValue = match (true) {
            is_null($normValue) => $this->fromNull(),
            is_string($normValue) => $this->fromString($normValue),
            is_integer($normValue) => $this->fromInteger($normValue),
            is_float($normValue) => $this->fromFloat($normValue),
            is_bool($normValue) => $this->fromBool($normValue),
            default => throw $this->throwException($value),
        };

        return $this->mwPipeline($resValue);
    }

    private function mwPipeline(mixed $value): mixed
    {
        return count($this->mw) === 0
            ? $value
            : array_reduce(
                $this->mw,
                static function (mixed $carry, callable|array $cb) {
                    if (is_callable($cb)) {
                        return $cb($carry);
                    }

                    $fn = array_shift($cb);
                    if (!is_callable($fn)) {
                        return $carry;
                    }

                    return $fn($carry, ...$cb);
                },
                $value
            );
    }

    public function fromString(string $value): mixed
    {
        $from = trim($value);
        $to = $this->fromStringFilter($from);
        if ($to === null) {
            throw $this->throwException($value);
        }

        return $to;
    }

    abstract public function fromNull(): mixed;

    abstract protected function fromStringFilter(string $value): mixed;

    abstract public function fromFloat(float $value): mixed;

    abstract public function fromInteger(int $value): mixed;

    abstract public function fromBool(bool $value): mixed;

    protected function checkForScalar(mixed $value): void
    {
        if (!is_scalar($value) && $value !== null) {
            throw new Exceptions\NeedScalarValue(static::class, $value);
        }
    }

    /**
     * @throws Exceptions\FailedNormalize
     */
    protected function throwException(mixed $value): Exceptions\FailedNormalize
    {
        return new Exceptions\FailedNormalize(static::class, $value);
    }

    public static function type(): string
    {
        return static::TYPE;
    }
}
