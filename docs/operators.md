# operators

## Table Of Contents

- [and_](https://github.com/tarsana/functional/blob/master/docs/operators.md#and_)

- [or_](https://github.com/tarsana/functional/blob/master/docs/operators.md#or_)

- [not](https://github.com/tarsana/functional/blob/master/docs/operators.md#not)

- [eq](https://github.com/tarsana/functional/blob/master/docs/operators.md#eq)

- [notEq](https://github.com/tarsana/functional/blob/master/docs/operators.md#notEq)

- [eqq](https://github.com/tarsana/functional/blob/master/docs/operators.md#eqq)

- [notEqq](https://github.com/tarsana/functional/blob/master/docs/operators.md#notEqq)

- [lt](https://github.com/tarsana/functional/blob/master/docs/operators.md#lt)

- [lte](https://github.com/tarsana/functional/blob/master/docs/operators.md#lte)

- [gt](https://github.com/tarsana/functional/blob/master/docs/operators.md#gt)

- [gte](https://github.com/tarsana/functional/blob/master/docs/operators.md#gte)

## and_

```php
and_(bool $a, bool $b) : bool
```

```
Boolean -> Boolean -> Boolean
```

Returns `$a && $b`.

## or_

```php
or_(bool $a, bool $b) : bool
```

```
Boolean -> Boolean -> Boolean
```

Returns `$a || $b`.

## not

```php
not(bool $x) : bool
```

```
Boolean -> Boolean
```

Returns `!$x`.

## eq

```php
eq(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x == $y`.

## notEq

```php
notEq(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x != $y`.

## eqq

```php
eqq(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x === $y`.

## notEqq

```php
notEqq(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x !== $y`.

## lt

```php
lt(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x < $y`.

## lte

```php
lte(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x <= $y`.

## gt

```php
gt(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x > $y`.

## gte

```php
gte(mixed $a, mixed $b) : bool
```

```
* -> * -> Boolean
```

Returns `$x >= $y`.