# operators

This file contains operators as functions.

- [and_](#and_) - Returns `$a && $b`.

- [or_](#or_) - Returns `$a || $b`.

- [not](#not) - Returns `!$x`.

- [eq](#eq) - Returns `$x == $y`.

- [notEq](#noteq) - Returns `$x != $y`.

- [eqq](#eqq) - Returns `$x === $y`.

- [notEqq](#noteqq) - Returns `$x !== $y`.

- [equals](#equals) - Returns `true` if the two elements have the same type and are deeply equivalent.

- [equalBy](#equalby) - Returns `true` if the results of applying `$fn` to `$a` and `$b` are deeply equal.

- [lt](#lt) - Returns `$a < $b`.

- [lte](#lte) - Returns `$a <= $b`.

- [gt](#gt) - Returns `$a > $b`.

- [gte](#gte) - Returns `$a >= $b`.

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

# equalBy

```php
equalBy() : [type] [description]
```

```
(a -> b) -> a -> a -> Boolean
```

Returns `true` if the results of applying `$fn` to `$a` and `$b` are deeply equal.

```php
$headEquals = F\equalBy(F\head());
$headEquals([1, 2], [1, 3]); //=> true
$headEquals([3, 2], [1, 3]); //=> false

$sameAge = F\equalBy(F\get('age'));
$foo = ['name' => 'foo', 'age' => 11];
$bar = ['name' => 'bar', 'age' => 13];
$baz = ['name' => 'baz', 'age' => 11];
$sameAge($foo, $bar); //=> false
$sameAge($foo, $baz); //=> true
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

