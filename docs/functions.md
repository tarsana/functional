# functions

Functions dealing with functions.

- [curry](#curry) - Returns a curried equivalent of the provided function.

- [__](#__) - Argument placeholder to use with curried functions.

- [apply](#apply) - Apply the provided function to the list of arguments.

- [pipe](#pipe) - Performs left-to-right function composition.

- [compose](#compose) - Performs right-to-left function composition.

- [identity](#identity) - A function that takes one argument and
returns exactly the given argument.

- [give](#give) - Returns a function which whenever called will return the specified value.

- [all](#all) - Takes many predicates and returns a new predicate that
returns `true` only if all predicates are satisfied.

- [any](#any) - Takes many predicates and returns a new predicate that
returns `true` if any of the predicates is satisfied.

- [complement](#complement) - Takes a function `f` and returns a function `g` so that if `f` returns
`x` for some arguments; `g` will return `! x` for the same arguments.

- [comparator](#comparator) - Takes a function telling if the first argument is less then the second, and return a compare function.

# curry

```php
curry(callable $fn) : callable
```

```
(* -> a) -> (* -> a)
```

Returns a curried equivalent of the provided function.

```php
$add = F\curry(function($x, $y) {
    return $x + $y;
});

$add(1, 2); //=> 3
$addFive = $add(5); // this is a function
$addFive(1); //=> 6

$data = [1, 2, 3, 4, 5];
$slice = F\curry('array_slice');
$itemsFrom = $slice($data);
$itemsFrom(2); //=> [3, 4, 5]
$itemsFrom(1, 2); //=> [2, 3, 4, 5]
// Notice that optional arguments are ignored !

$polynomial = F\curry(function($a, $b, $c, $x) {
    return $a * $x * $x + $b * $x + $c;
});
$f = $polynomial(0, 2, 1); // 2 * $x + 1
$f(5); //=> 11
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
$reduce = F\curry('array_reduce');
$sum = $reduce(F\__(), F\plus());
$sum([1, 2, 3, 4], 0); //=> 10

$polynomial = F\curry(function($a, $b, $c, $x) {
    return $a * $x * $x + $b * $x + $c;
});

$multiplier = $polynomial(0, F\__(), 0, F\__());
$triple = $multiplier(3);
$triple(5); //=> 15
$multipleOfThree = $multiplier(F\__(), 3);
$multipleOfThree(4); //=> 12
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
The result of pipe is **not curried**.
**Calling pipe() without any argument returns the `identity` function**.

```php
$double = function($x) { return 2 * $x; };
$addThenDouble = F\pipe(F\plus(), $double);
$addThenDouble(2, 3); //=> 10
```

# compose

```php
compose(callable $fns...) : callable
```

```
(((a, b, ...) -> o), (o -> p), ..., (y -> z)) -> ((a, b, ...) -> z)
```

Performs right-to-left function composition.

The rightmost function may have any arity;
the remaining functions must be unary.
The result of `compose` is **not curried**.
**Calling compose() without any argument returns the `identity` function**.

```php
$double = function($x) { return 2 * $x; };
$addThenDouble = F\compose($double, F\plus());
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
$betweenOneAndTen = F\all(F\lt(1), F\gt(10));
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

# complement

```php
complement(callable $fn) : callable
```

```
(* -> ... -> *) -> (* -> ... -> Boolean)
```

Takes a function `f` and returns a function `g` so that if `f` returns
`x` for some arguments; `g` will return `! x` for the same arguments.

Note that `complement($fn) == pipe($fn, not())`, So the resulting function is not curried !.
```php
$isOdd = function($number) {
    return 1 == $number % 2;
};

$isEven = F\complement($isOdd);

$isEven(5); //=> false
$isEven(8); //=> true
```

# comparator

```php
comparator(callable $fn) : callable
```

```
(a -> a -> Boolean) -> (a -> a -> Number)
```

Takes a function telling if the first argument is less then the second, and return a compare function.

A compare function returns `-1`, `0`, or `1` if the first argument is considered
to be respectively less than, equal to, or greater than the second.
```php
$users = [
    ['name' => 'foo', 'age' => 21],
    ['name' => 'bar', 'age' => 11],
    ['name' => 'baz', 'age' => 15]
];

usort($users, F\comparator(function($a, $b){
    return $a['age'] < $b['age'];
}));

F\map(F\get('name'), $users); //=> ['bar', 'baz', 'foo']
```

