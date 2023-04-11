<?php

declare(strict_types=1);

namespace TypeNormalizer\Exceptions;

final class NeedScalarValue extends TypeNormalizerException
{
    public function __construct(string $normalizer, mixed $value)
    {
        $this->value = $value;
        parent::__construct($normalizer, $value, 'Value type should be scalar. Given ' . $this->valueType());
    }
}
