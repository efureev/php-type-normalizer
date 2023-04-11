<?php

declare(strict_types=1);

namespace TypeNormalizer\Tests;

use PHPUnit\Framework\TestCase;
use TypeNormalizer\Exceptions\FailedNormalize;
use TypeNormalizer\TypeNormalizer;

class IntegerTest extends TestCase
{
    /**
     * @return array{mixed, int}[]
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
            [' 132323     ', 132323],
            ['-132323', -132323],
            ['   -132323  ', -132323],
            [true, 1],
            ['true', 1],
            ['  true   ', 1],
            [false, 0],
            ['false', 0],
            ['  false ', 0],
            [0, 0],
            ['0', 0],
            [' 0   ', 0],
            [1.00, 1],
            ['1.00', 1],
            [' 1.00   ', 1],
            [0.00, 0],
            ['0.00', 0],
            ['   0.00 ', 0],
            [null, 0],
            ['', 0],
            ['    ', 0],
        ];
    }

    /**
     * @return array{mixed}[]
     */
    public static function dataProviderForException(): array
    {
        return [
            [1.2],
            ['1.2'],
            ['test'],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForNormal
     */
    public function shouldNotTriggerException(mixed $given, int $expect): void
    {
        self::assertEquals($expect, TypeNormalizer::toInt($given));
    }

    /**
     * @test
     * @dataProvider dataProviderForException
     */
    public function shouldTriggerException(mixed $given): void
    {
        $this->expectException(FailedNormalize::class);
        TypeNormalizer::toInt($given);
    }

    /**
     * @return array<array{mixed, int, (callable|string|array{callable|string, mixed}|array{array<callable|string>})[]}>
     */
    public static function dataProviderForNormalWithMW(): array
    {
        return [
            [132323, 132323, []],
            [-132323, 132323, ['abs']],
            ['  -132323  ', 132323, ['abs']],
            ['  -2  ', 2, ['abs']],
            ['  -2  ', 2, [['abs']]],
            ['  -2  ', 4, ['abs', ['pow', 2]]],
            [1, 10, [static fn(int $item) => $item * 10]],
            ['-2', 5, ['abs', [fn(int $item, int $plus) => pow($item, 2) + $plus, 1]]]
        ];
    }


    /**
     * @test
     * @dataProvider dataProviderForNormalWithMW
     *
     * @param mixed $given
     * @param int $expect
     * @param callable[] $mw
     * @return void
     */
    public function shouldNormalWithMiddlewares(mixed $given, int $expect, array $mw): void
    {
        self::assertEquals($expect, TypeNormalizer::toInt($given, ...$mw));
    }
}
