<?php

declare(strict_types=1);

namespace TypeNormalizer\Tests;

use PHPUnit\Framework\TestCase;
use TypeNormalizer\Exceptions\FailedNormalize;
use TypeNormalizer\TypeNormalizer;

class BooleanTest extends TestCase
{
    /**
     * @return array{mixed, bool}[]
     */
    public static function dataProviderForNormal(): array
    {
        return [
            [1, true],
            ['1', true],
            [' 1  ', true],
            [true, true],
            ['true', true],
            ['  true  ', true],
            [1.00, true],
            ['1.00', true],
            ['  1.00 ', true],
            [false, false],
            ['false', false],
            ['  false ', false],
            [0, false],
            ['0', false],
            [' 0 ', false],
            [0.00, false],
            ['0.00', false],
            [' 0.00 ', false],
            ['', false],
            ['    ', false],
            [null, false],
        ];
    }

    /**
     * @return array{mixed}[]
     */
    public static function dataProviderForException(): array
    {
        return [
            [2],
            [1202010],
            ['1202010'],
            ['0.0 0'],
            ['-1202010'],
            [1.2],
            ['1.2'],
            ['test'],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForNormal
     */
    public function shouldNotTriggerException(mixed $given, bool $expect): void
    {
        self::assertEquals($expect, TypeNormalizer::toBool($given));
    }

    /**
     * @test
     * @dataProvider dataProviderForException
     */
    public function shouldTriggerException(mixed $given): void
    {
        $this->expectException(FailedNormalize::class);
        TypeNormalizer::toBool($given);
    }
}
