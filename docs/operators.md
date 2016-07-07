# operators

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

## type

```php
type(mixed $data) : string
```



Gets the type of the given argument.
```php
type(null); // 'Null'
type(true); // 'Boolean'
type(false); // 'Boolean'
type('Hello World'); // 'String'
type(1234); // 'Number'
type('123'); // 'String'
type(function($x) {return $x;}); // 'Function'
type(new \stdClass); // 'Object'
type(['name' => 'Foo', 'age' => 21]); // 'ArrayObject'
type(['Hello', 'World', 123, true]); // 'List'
type(['name' => 'Foo', 'Hello', 'Mixed']); // 'Array'
type(fopen('php://temp')); // 'Resource'
type(Error::of('Ooops !')); // 'Error'
// Anything else is 'Unknown'
```