<?php

declare(strict_types=1);

namespace TypeNormalizer\Tests;

use PHPUnit\Framework\TestCase;
use TypeNormalizer\Exceptions\FailedNormalize;
use TypeNormalizer\TypeNormalizer;

class FloatTest extends TestCase
{
    /**
     * @return array{mixed, float}[]
     */
    public static function dataProviderForNormal(): array
    {
        return [
            [1, 1],
            [132323, 132323],
            [-132323, -132323],
            ['1', 1],
            [' 1  ', 1],
            ['132323', 132323],
            ['132323   ', 132323],
            ['  -132323  ', -132323],
            [' -132323  ', -132323],
            [true, 1],
            ['true', 1],
            ['  true  ', 1],
            [false, 0],
            ['false', 0],
            ['   false ', 0],
            [0, 0],
            ['0', 0],
            ['0  ', 0],
            [1.00, 1],
            ['1.00', 1],
            ['   1.00 ', 1],
            [211.12, 211.12],
            ['211.12', 211.12],
            [' 211.12 ', 211.12],
            [0.00, 0],
            ['  0.00 ', 0],
            [null, 0],
            ['', 0],
            ['   ', 0],
        ];
    }

    /**
     * @return array{mixed}[]
     */
    public static function dataProviderForException(): array
    {
        return [
            ['test'],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForNormal
     */
    public function shouldNotTriggerException(mixed $given, float $expect): void
    {
        self::assertEquals($expect, TypeNormalizer::toFloat($given));
    }

    /**
     * @test
     * @dataProvider dataProviderForException
     */
    public function shouldTriggerException(mixed $given): void
    {
        $this->expectException(FailedNormalize::class);
        TypeNormalizer::toFloat($given);
    }
}
