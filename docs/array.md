# array

## value

```php
value(string $name, array $array) : mixed
```

```
String -> [key => *] -> *
```

Gets the value of a key.

## map

```php
map(callable $fn, array $array) : array
```

```
(a -> b) -> [a] -> [b]
```

Curried version of `array_map()`.

## filter

```php
filter(callable $fn, array $array) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Curried version of `array_filter` with modified order of
arguments. The callback is the first argument then the array.

## reduce

```php
reduce(callable $fn, mixed $initial, array $array) : array
```

```
(* -> a -> *) -> * -> [a] -> *
```

Curried version of `array_reduce` with modified order of
arguments ($callback, $initial, $array).

## each

```php
each(callable $fn, array $array) : array
```

```
(a -> *) -> [a] -> [a]
```

Applies the callback to each item and returns the original array.

## head

```php
head(array|string $array) : mixed
```

```
[a] -> a
String -> String
```

Returns the first item of the given array or string.

## last

```php
last(array|string $array) : mixed
```

```
[a] -> a
String -> String
```

Returns the last item of the given array or string.

## init

```php
init(array|string $array) : array
```

```
[a] -> a
String -> String
```

Returns all but the last element of the given array or string.

## tail

```php
tail(array|string $array) : array
```

```
[a] -> a
String -> String
```

Returns all but the first element of the given array or string.

## reverse

```php
reverse(array|string $array) : array
```

```
[a] -> [a]
String -> String
```

Alias of `array_reverse()` and `strrev()`.

## length

```php
length(array|string $array) : int
```

```
[a] -> Number
String -> Number
```

Alias for `count()` and `strlen()`.