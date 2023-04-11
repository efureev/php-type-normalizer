<?php

declare(strict_types=1);

namespace TypeNormalizer\Exceptions;

use TypeNormalizer\AbstractNormalizer;
use TypeNormalizer\Utils;

class TypeNormalizerException extends \LogicException
{
    public function __construct(
        /**
         * @param class-string<AbstractNormalizer>
         */
        public readonly string $normalizer,
        public mixed $value,
        string $message = 'Type Normalizer Exception',
    ) {
        parent::__construct($message);
    }

    public function normalizer(): string
    {
        return Utils::classBasename($this->normalizer);
    }

    public function normalizerType(): string
    {
        return ($this->normalizer)::type();
    }

    public function valueType(): string
    {
        $type = gettype($this->value);
        if (is_object($this->value)) {
            return "$type:" . $this->value::class;
        }

        return $type;
    }
}
