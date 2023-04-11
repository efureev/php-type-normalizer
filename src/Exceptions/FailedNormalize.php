<?php

declare(strict_types=1);

namespace TypeNormalizer\Exceptions;

use TypeNormalizer\Utils;

final class FailedNormalize extends TypeNormalizerException
{
    public function __construct(
        string $normalizer,
        mixed $value,
        string $message = 'Data could not be normalized'
    ) {
        parent::__construct($normalizer, $value, "$message: " . Utils::classBasename($normalizer));
    }
}
