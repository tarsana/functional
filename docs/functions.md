# functions

## Table Of Contents

- [curry](https://github.com/tarsana/functional/blob/master/docs/functions#curry)

- [__](https://github.com/tarsana/functional/blob/master/docs/functions#__)

- [apply](https://github.com/tarsana/functional/blob/master/docs/functions#apply)

- [pipe](https://github.com/tarsana/functional/blob/master/docs/functions#pipe)

- [identity](https://github.com/tarsana/functional/blob/master/docs/functions#identity)

## curry

```php
curry(callable $fn) : callable
```

```
(* -> a) -> (* -> a)
```

Returns a curried equivalent of the provided function.
```php
$add = curry(function($x, $y){
    return $x + $y;
});
$addFive = $add(5); // a function
$addFive(5); // 10
$add(5, 5) // 10
```

## __

```php
__() : \Cypress\Curry\Placeholder
```

```
* -> Placeholder
```

Argument placeholder to use with curried functions.
```php
$minus = curry(function ($x, $y) { return $x - $y; });
$decrement = $minus(__(), 1);
$decrement(10) // 9

$reduce = curry('array_reduce');
$sum = $reduce(__(), 'Tarsana\Functional\plus');
$sum([1, 2, 3, 4], 0) // 10
```

## apply

```php
apply(callable $fn, array $args) : mixed
```

```
(*... -> a) -> [*] -> a
```

Apply the provided function to the list of arguments.
```php
apply('strlen', ['Hello']) // 5
$replace = apply('str_replace');
$replace(['l', 'o', 'Hello']) // 'Heooo'
```

## pipe

```php
pipe(callable ...$fns) : callable
```

```
(((a, b, ...) -> o), (o -> p), ..., (y -> z)) -> ((a, b, ...) -> z)
```

Performs left-to-right function composition.
The leftmost function may have any arity;
the remaining functions must be unary.
The result of pipe is curried.
**Calling pipe() without any argument throws Tarsana\Functional\Exceptions\InvalidArgument**
```php
function add($x, $y) { return $x + $y; }
$double = function($x) { return 2 * $x; };
$addThenDouble = pipe('add', $double);
$addThenDouble(2, 3) // 10
```

## identity

```php
identity() : mixed
```

```
* -> *
```

A function that takes one argument and
returns exactly the given argument.
```php
identity('Hello') // 'Hello'
identity([1, 2, 3]) // [1, 2, 3]
identity(null) // null
```