# Type Normalizer

![](https://img.shields.io/badge/php-8.1-blue.svg)
![PHP Package](https://github.com/efureev/php-type-normalizer/workflows/PHP%20Package/badge.svg?branch=master)
[![Latest Stable Version](https://poser.pugx.org/efureev/php-type-normalizer/v/stable?format=flat)](https://packagist.org/packages/efureev/php-type-normalizer)
[![Total Downloads](https://poser.pugx.org/efureev/php-type-normalizer/downloads)](https://packagist.org/packages/efureev/php-type-normalizer)

## Description

It transforms mixed types into you need.

## Install

For php >= 8.2

```bash
composer require efureev/php-type-normalizer "^1.0"
```

## Basic Using

```php
TypeNormalizer::toInt('1') // 1
TypeNormalizer::toBool('1') // true
TypeNormalizer::toFloat('1.2') // 1.2
TypeNormalizer::toString(true) // 'true'
```

## Using with Middleware

Middleware may be any `callable` variable.

```php
TypeNormalizer::toInt('  -132323  ', 'abs'); // 132323
TypeNormalizer::toInt('  -2  ', 'abs', ['pow', 2]); // 4
TypeNormalizer::toString('  test   ', 'trim', 'mb_strtoupper'); // 'TEST'
TypeNormalizer::toString(null, static fn(string $item) => '<none>'); // '<none>'
```

Middleware may be defined with params:

```php
TypeNormalizer::toInt('  -2  ', 'abs', ['pow', 2]); // 4
TypeNormalizer::toInt('  -2  ', 'abs', [fn(int $item, int $plus) => pow($item, 2) + $plus, 1]); // 5
```

## Transformation Map

### Integer

```php
TypeNormalizer::toInt(1) // 1 
TypeNormalizer::toInt(211) // 211 
TypeNormalizer::toInt('211') // 211 
TypeNormalizer::toInt('    211 ') // 211 
TypeNormalizer::toInt('1') // 1 
TypeNormalizer::toInt('true') // 1
TypeNormalizer::toInt('  true  ') // 1
TypeNormalizer::toInt(true) // 1
TypeNormalizer::toInt(1.00) // 1
TypeNormalizer::toInt('1.00') // 1

TypeNormalizer::toInt(false) // 0
TypeNormalizer::toInt('  false  ') // 0
TypeNormalizer::toInt('     0  ') // 0
TypeNormalizer::toInt(0) // 0
TypeNormalizer::toInt('') // 0
TypeNormalizer::toInt('   ') // 0
TypeNormalizer::toInt(null) // 0

TypeNormalizer::toInt('hello') // exception
TypeNormalizer::toInt('1.2') // exception
TypeNormalizer::toInt(1.2) // exception
```

### Boolean

```php
TypeNormalizer::toBool(1) // true
TypeNormalizer::toBool(' 1  ') // true
TypeNormalizer::toBool(true) // true
TypeNormalizer::toBool('  true ') // true
TypeNormalizer::toBool(' 1.00  ') // true
TypeNormalizer::toBool(1.00) // true

TypeNormalizer::toBool(0) // false
TypeNormalizer::toBool(false) // false
TypeNormalizer::toBool('  false  ') // false
TypeNormalizer::toBool('   0 ') // false
TypeNormalizer::toBool('   0.0 ') // false
TypeNormalizer::toBool('') // false
TypeNormalizer::toBool('   ') // false
TypeNormalizer::toBool(null) // false

TypeNormalizer::toBool('hello') // exception
TypeNormalizer::toBool(1.2) // exception
TypeNormalizer::toBool(22) // exception
TypeNormalizer::toBool('22') // exception
TypeNormalizer::toBool(' 0. 00') // exception
```

### Float

```php
TypeNormalizer::toFloat(1) // 1
TypeNormalizer::toFloat(' 1  ') // 1
TypeNormalizer::toFloat(' -132323  ') // -1
TypeNormalizer::toFloat(true) // 1
TypeNormalizer::toFloat('  true ') // 1
TypeNormalizer::toFloat(' 1.00  ') // 1
TypeNormalizer::toFloat(' 21.21  ') // 21.21
TypeNormalizer::toFloat(1.00) // 1

TypeNormalizer::toFloat(0) // 0
TypeNormalizer::toFloat(false) // 0
TypeNormalizer::toFloat('  false  ') // 0
TypeNormalizer::toFloat('   0 ') // 0
TypeNormalizer::toFloat('   0.0 ') // 0
TypeNormalizer::toFloat('') // 0
TypeNormalizer::toFloat('   ') // 0
TypeNormalizer::toFloat(null) // 0

TypeNormalizer::toFloat('hello') // exception
```

## Test

```bash
composer test
composer test-cover # with coverage
```
