# math

## Table Of Contents

- [plus](https://github.com/tarsana/functional/blob/master/docs/math.md#plus)

- [minus](https://github.com/tarsana/functional/blob/master/docs/math.md#minus)

- [negate](https://github.com/tarsana/functional/blob/master/docs/math.md#negate)

- [multiply](https://github.com/tarsana/functional/blob/master/docs/math.md#multiply)

- [divide](https://github.com/tarsana/functional/blob/master/docs/math.md#divide)

- [modulo](https://github.com/tarsana/functional/blob/master/docs/math.md#modulo)

- [sum](https://github.com/tarsana/functional/blob/master/docs/math.md#sum)

- [product](https://github.com/tarsana/functional/blob/master/docs/math.md#product)

## plus

```php
plus(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computes `$x + $y`.
```php
$plusTwo = plus(2);
$plusTwo(5); // 7
```

## minus

```php
minus(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computues `$x - $y`.
```php
minus(7, 2); // 5
```

## negate

```php
negate(int|float $x) : int|float
```

```
Number -> Number
```

Computes `- $x`.
```php
negate(5); // -5
negate(-7); // 7
```

## multiply

```php
multiply(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computes `$x * $y`.
```php
$double = multiply(2);
$double(5); // 10
```

## divide

```php
divide(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computes `$x / $y`.
```php
divide(10, 2); // 5
```

## modulo

```php
modulo(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computes `$x % $y`.
```php
modulo(10, 3); // 1
```

## sum

```php
sum(array $numbers) : int|float
```

```
[Number] -> Number
```

Computes the sum of an array of numbers.
```php
sum([1, 2, 3, 4]) // 10
sum([]) // 0
```

## product

```php
product(array $numbers) : int|float
```

```
[Number] -> Number
```

Computes the product of an array of numbers.
```php
product([1, 2, 3, 4]) // 24
product([]) // 1
```