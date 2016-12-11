#functions

Functions dealing with functions.

- [curry](#curry) Returns a curried equivalent of the provided function.

- [__](#__) Argument placeholder to use with curried functions.

- [apply](#apply) Apply the provided function to the list of arguments.

- [pipe](#pipe) Performs left-to-right function composition.

- [identity](#identity) A function that takes one argument and
returns exactly the given argument.

- [give](#give) Returns a function which whenever called will return the specified value.

- [all](#all) Takes many predicates and returns a new predicate that
returns `true` only if all predicates are satisfied.

- [any](#any) Takes many predicates and returns a new predicate that
returns `true` if any of the predicates is satisfied.

# curry

```php
curry(callable $fn) : callable
```

```
(* -> a) -> (* -> a)
```

Returns a curried equivalent of the provided function.

```php
// A closure
$add = F\curry(function($x, $y) {
    return $x + $y;
});

$add(1, 2); //=> 3
$addFive = $add(5); // this is a function
$addFive(1); //=> 6

$sum = F\curry(function() use($add) {
    $numbers = func_get_args();
    return F\reduce($add, 0, $numbers);
});

$sum(); //=> 0
$sum(1, 2, 3, 4); //=> 10
```

# __

```php
__() : Tarsana\Functional\Placeholder
```

```
* -> Placeholder
```

Argument placeholder to use with curried functions.

```php
$minus = F\curry(function ($x, $y) { return $x - $y; });
$decrement = $minus(F\__(), 1);
$decrement(10); //=> 9

$reduce = F\curry('array_reduce');
$sum = $reduce(F\__(), F\plus());
$sum([1, 2, 3, 4], 0); //=> 10
```

# apply

```php
apply(callable $fn, array ) : mixed
```

```
(*... -> a) -> [*] -> a
```

Apply the provided function to the list of arguments.

```php
F\apply('strlen', ['Hello']); //=> 5
$replace = F\apply('str_replace');
$replace(['l', 'o', 'Hello']); //=> 'Heooo'
```

# pipe

```php
pipe(callable $fns...) : callable
```

```
(((a, b, ...) -> o), (o -> p), ..., (y -> z)) -> ((a, b, ...) -> z)
```

Performs left-to-right function composition.

The leftmost function may have any arity;
the remaining functions must be unary.
The result of pipe is curried.
**Calling pipe() without any argument returns the `identity` function**
```php
$double = function($x) { return 2 * $x; };
$addThenDouble = F\pipe(F\plus(), $double);
$addThenDouble(2, 3); //=> 10
```

# identity

```php
identity() : mixed
```

```
* -> *
```

A function that takes one argument and
returns exactly the given argument.

```php
F\identity('Hello'); //=> 'Hello'
F\identity([1, 2, 3]); //=> [1, 2, 3]
F\identity(null); //=> null
```

# give

```php
give(mixed $value) : callable
```

```
a -> (* -> a)
```

Returns a function which whenever called will return the specified value.

```php
$five = F\give(5);
$five(); //=> 5
$null = F\give(null);
$null(); //=> null
```

# all

```php
all(callable $predicates...) : callable
```

```
((a -> Boolean), ..., (a -> Boolean)) -> (a -> Boolean)
```

Takes many predicates and returns a new predicate that
returns `true` only if all predicates are satisfied.

If no predicate is given as argument, this function
will return an always passing predicate.
```php
$betweenOneAndTen = F\all(F\gte(F\__(), 1), F\lte(F\__(), 10));
$betweenOneAndTen(5); //=> true
$betweenOneAndTen(0); //=> false
$alwaysTrue = F\all();
$alwaysTrue(1); //=> true
$alwaysTrue(null); //=> true
```

# any

```php
any(callable $predicates...) : callable
```

```
((a -> Boolean), ..., (a -> Boolean)) -> (a -> Boolean)
```

Takes many predicates and returns a new predicate that
returns `true` if any of the predicates is satisfied.

If no predicate is given as argument, this function
will return an always non-passing predicate.
```php
$startsOrEndsWith = function($text) {
    return F\any(F\startsWith($text), F\endsWith($text));
};
$test = $startsOrEndsWith('b');
$test('bar'); //=> true
$test('bob'); //=> true
$test('foo'); //=> false
$alwaysFlase = F\any();
$alwaysFlase(1); //=> false
$alwaysFlase(null); //=> false
```

