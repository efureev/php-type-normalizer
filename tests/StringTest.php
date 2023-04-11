<?php

declare(strict_types=1);

namespace TypeNormalizer\Tests;

use PHPUnit\Framework\TestCase;
use TypeNormalizer\Exceptions\NeedScalarValue;
use TypeNormalizer\TypeNormalizer;

class StringTest extends TestCase
{
    /**
     * @return array{mixed, string}[]
     */
    public static function dataProviderForNormal(): array
    {
        return [
            [1, "1"],
            [132323, "132323"],
            [-132323, "-132323"],
            ['1', '1'],
            ['1', '1'],
            ['132323', '132323'],
            ['-132323', '-132323'],
            [true, 'true'],
            ['true', 'true'],
            [false, 'false'],
            ['false', 'false'],
            [0, '0'],
            ['0', '0'],
            [1.00, '1'],
            ['1.00', '1.00'],
            ['test', 'test'],
            ['  test   ', '  test   '],
            [211.12, '211.12'],
            ['211.12', '211.12'],
            ['  211.12', '  211.12'],
            [-211.12, '-211.12'],
            [0.00, '0'],
            ['0.00', '0.00'],
            ['  0.00  ', '  0.00  '],
            [null, ''],
            ['', ''],
            ['   ', '   '],
        ];
    }

    /**
     * @return array{mixed, string, (callable|array{callable, mixed})[]}[]
     */
    public static function dataProviderForNormalWithMW(): array
    {
        return [
            [1.00, '1.00', [static fn(string $item) => "$item.00"]],
            ['  test   ', '  test   ', []],
            ['  test   ', 'test', ['trim']],
            ['  test   ', 'TEST', ['trim', 'mb_strtoupper']],
            ['  test   ', '  TEST   ', ['mb_strtoupper']],
            ['  test   ', 'test   ', ['ltrim']],
            [null, '<none>', [static fn(string $item) => "<none>"]],
        ];
    }

    /**
     * @return array{mixed}[]
     */
    public static function dataProviderForException(): array
    {
        return [
            [new class {
            }],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderForNormal
     */
    public function shouldNotTriggerException(mixed $given, string $expect): void
    {
        self::assertEquals($expect, TypeNormalizer::toString($given));
    }

    /**
     * @test
     * @dataProvider dataProviderForException
     */
    public function shouldTriggerException(mixed $given): void
    {
        $this->expectException(NeedScalarValue::class);
        TypeNormalizer::toString($given);
    }

    /**
     * @test
     * @dataProvider dataProviderForNormalWithMW
     *
     * @param mixed $given
     * @param string $expect
     * @param callable[] $mw
     * @return void
     */
    public function shouldNormalWithMiddlewares(mixed $given, string $expect, array $mw): void
    {
        self::assertEquals($expect, TypeNormalizer::toString($given, ...$mw));
    }
}
