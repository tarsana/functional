# functions

## curry

```php
curry(callable $fn) : callable
```

```
(* -> a) -> (* -> a)
```

Returns a curried equivalent of the provided function.

## __

```php
__() : \Cypress\Curry\Placeholder
```

```
* -> Placeholder
```

Argument placeholder.

## apply

```php
apply(callable $fn, array $args) : mixed
```

```
(*... -> a) -> [*] -> a
```

Apply the provided function to the list of arguments.

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

## identity

```php
identity() : callable
```

```
* -> *
```

A function that takes one argument and
returns exactly the given argument.