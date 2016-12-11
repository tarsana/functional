#Operators

This file contains operators as functions.

- [and_](#and)

- [or_](#or)

- [not](#not)

- [eq](#eq)

- [notEq](#not-eq)

- [eqq](#eqq)

- [notEqq](#not-eqq)

- [equals](#equals)

- [lt](#lt)

- [lte](#lte)

- [gt](#gt)

- [gte](#gte)

# and_

```php
and_(bool $a, bool $b) : bool
```

```
Boolean -> Boolean -> Boolean
```

Returns `$a && $b`.

```php
$isTrue = F\and_(true);
$isTrue(false); //=> false
$isTrue(true); //=> true
```

# or_

```php
or_(bool $a, bool $b) : bool
```

```
Boolean -> Boolean -> Boolean
```

Returns `$a || $b`.

```php
$isTrue = F\or_(false);
$isTrue(false); //=> false
$isTrue(true); //=> true
```

# not

```php
not(bool $x) : bool
```

```
Boolean -> Boolean
```

Returns `!$x`.

```php
F\map(F\not(), [true, false, true]); //=> [false, true, false]
```

# eq

```php
eq(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x == $y`.

```php
F\eq('10', 10); //=> true
```

# notEq

```php
notEq(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x != $y`.

```php
F\notEq('Hi', 'Hello'); //=> true
```

# eqq

```php
eqq(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x === $y`.

```php
F\eqq(10, '10'); //=> false
```

# notEqq

```php
notEqq(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x !== $y`.

```php
F\notEqq(10, '10'); //=> true
```

# equals

```php
equals(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `true` if the two elements have the same type and are deeply equivalent.

```php
$a = (object) ['a' => 1, 'b' => (object) ['c' => 'Hello'], 'd' => false];
$b = (object) ['a' => 1, 'b' => (object) ['c' => 'Hi'], 'd' => false];
$c = (object) ['a' => 1, 'b' => ['c' => 'Hello'], 'd' => false];
// should have the same type
F\equals(5, '5'); //=> false
F\equals([1, 2, 3], [1, 2, 3]); //=> true
// should have the same order
F\equals([1, 3, 2], [1, 2, 3]); //=> false
F\equals($a, $b); //=> false
F\equals($a, $c); //=> false
$b->b->c = 'Hello';
F\equals($a, $b); //=> true
```

# lt

```php
lt(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$a < $b`.

```php
F\lt(3, 5); //=> true
F\lt(5, 5); //=> false
```

# lte

```php
lte(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$a <= $b`.

```php
F\lte(3, 5); //=> true
F\lte(5, 5); //=> true
```

# gt

```php
gt(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$a > $b`.

```php
F\gt(5, 3); //=> true
F\gt(5, 5); //=> false
```

# gte

```php
gte(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$a >= $b`.

```php
F\gte(5, 3); //=> true
F\gte(5, 5); //=> true
```

