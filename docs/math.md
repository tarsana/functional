# math

## plus

```php
plus(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computes `$x + $y`.
```
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
```
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
```
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
```
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
```
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
```
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

## product

```php
product(array $numbers) : int|float
```

```
[Number] -> Number
```

Computes the product of an array of numbers.