#Math

Basic Math functions.

- [plus](#plus)

- [minus](#minus)

- [negate](#negate)

- [multiply](#multiply)

- [divide](#divide)

- [modulo](#modulo)

- [sum](#sum)

- [product](#product)

# plus

```php
plus(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computes `$x + $y`.

```php
$plusTwo = F\plus(2);
$plusTwo(5); //=> 7
```

# minus

```php
minus(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computues `$x - $y`.

```php
F\minus(7, 2); //=> 5
```

# negate

```php
negate(int|float $x) : int|float
```

```
Number -> Number
```

Computes `- $x`.

```php
F\negate(5); //=> -5
F\negate(-7); //=> 7
```

# multiply

```php
multiply(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computes `$x * $y`.

```php
$double = F\multiply(2);
$double(5); //=> 10
```

# divide

```php
divide(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computes `$x / $y`.

```php
F\divide(10, 2); //=> 5
```

# modulo

```php
modulo(int|float $x, int|float $y) : int|float
```

```
Number -> Number -> Number
```

Computes `$x % $y`.

```php
F\modulo(10, 3); //=> 1
```

# sum

```php
sum(array $numbers) : int|float
```

```
[Number] -> Number
```

Computes the sum of an array of numbers.

```php
F\sum([1, 2, 3, 4]); //=> 10
F\sum([]); //=> 0
```

# product

```php
product(array $numbers) : int|float
```

```
[Number] -> Number
```

Computes the product of an array of numbers.

```php
F\product([1, 2, 3, 4]); //=> 24
F\product([]); //=> 1
```

