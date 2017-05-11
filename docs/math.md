# math

Basic Math functions.

- [plus](#plus) - Computes `$x + $y`.

- [minus](#minus) - Computues `$x - $y`.

- [negate](#negate) - Computes `- $x`.

- [multiply](#multiply) - Computes `$x * $y`.

- [divide](#divide) - Computes `$x / $y`.

- [modulo](#modulo) - Computes `$x % $y`.

- [sum](#sum) - Computes the sum of an array of numbers.

- [product](#product) - Computes the product of an array of numbers.

- [min](#min) - Computes the minimum of two numbers.

- [minBy](#minby) - Computes the minimum of two elements using a function.

- [max](#max) - Computes the maximum of two numbers.

- [maxBy](#maxby) - Computes the maximum of two elements using a function.

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

# min

```php
min(number $a, number $b) : number
```

```
Number -> Number -> Number
```

Computes the minimum of two numbers.

```php
F\min(1, 3); //=> 1
F\min(1, -3); //=> -3
```

# minBy

```php
minBy(callable $fn, mixed $a, mixed $b) : mixed
```

```
(a -> Number) -> a -> a -> a
```

Computes the minimum of two elements using a function.

```php
F\minBy(F\length(), 'Hello', 'Hi'); //=> 'Hi'
F\minBy('abs', 1, -3); //=> 1
```

# max

```php
max(number $a, number $b) : number
```

```
Number -> Number -> Number
```

Computes the maximum of two numbers.

```php
F\max(1, 3); //=> 3
F\max(1, -3); //=> 1
```

# maxBy

```php
maxBy(callable $fn, mixed $a, mixed $b) : mixed
```

```
(a -> Number) -> a -> a -> a
```

Computes the maximum of two elements using a function.

```php
F\maxBy(F\length(), 'Hello', 'Hi'); //=> 'Hello'
F\maxBy('abs', 1, -3); //=> -3
```

