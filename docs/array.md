# array

## Table Of Contents

- [value](https://github.com/tarsana/functional/blob/master/docs/array.md#value)

- [map](https://github.com/tarsana/functional/blob/master/docs/array.md#map)

- [filter](https://github.com/tarsana/functional/blob/master/docs/array.md#filter)

- [reduce](https://github.com/tarsana/functional/blob/master/docs/array.md#reduce)

- [each](https://github.com/tarsana/functional/blob/master/docs/array.md#each)

- [head](https://github.com/tarsana/functional/blob/master/docs/array.md#head)

- [last](https://github.com/tarsana/functional/blob/master/docs/array.md#last)

- [init](https://github.com/tarsana/functional/blob/master/docs/array.md#init)

- [tail](https://github.com/tarsana/functional/blob/master/docs/array.md#tail)

- [reverse](https://github.com/tarsana/functional/blob/master/docs/array.md#reverse)

- [length](https://github.com/tarsana/functional/blob/master/docs/array.md#length)

- [all](https://github.com/tarsana/functional/blob/master/docs/array.md#all)

- [any](https://github.com/tarsana/functional/blob/master/docs/array.md#any)

- [concat](https://github.com/tarsana/functional/blob/master/docs/array.md#concat)

- [append](https://github.com/tarsana/functional/blob/master/docs/array.md#append)

- [take](https://github.com/tarsana/functional/blob/master/docs/array.md#take)

- [toPairs](https://github.com/tarsana/functional/blob/master/docs/array.md#toPairs)

- [chain](https://github.com/tarsana/functional/blob/master/docs/array.md#chain)

## value

```php
value(string $name, array $array) : mixed
```

```
String -> [key => *] -> *
```

Gets the value of a key.
```php
$data = [
    ['name' => 'foo', 'type' => 'test'],
    ['name' => 'bar', 'type' => 'test']
];
$nameOf = value('name');
value(0, $data) // ['name' => 'foo', 'type' => 'test']
$nameOf($data[1]) // 'bar'
```

## map

```php
map(callable $fn, array $array) : array
```

```
(a -> b) -> [a] -> [b]
```

Curried version of `array_map()`.
```php
$doubles = map(function($x) { return 2 * $x; });
$doubles([1, 2, 3, 4]) // [2, 4, 6, 8]
```

## filter

```php
filter(callable $fn, array $array) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Curried version of `array_filter` with modified order of
arguments. The callback is the first argument then the array.
```php
$array = [1, 'aa', 3, [4, 5]];
$numeric = F\filter('is_numeric');
$numeric($array) // [1, 3]
```

## reduce

```php
reduce(callable $fn, mixed $initial, array $array) : array
```

```
(* -> a -> *) -> * -> [a] -> *
```

Curried version of `array_reduce` with modified order of
arguments ($callback, $initial, $array).
```php
$array = [1, 2, 3, 4];
$sum = reduce('Tarsana\Functional\plus', 0);
$sum($array) // 10
```

## each

```php
each(callable $fn, array $array) : array
```

```
(a -> *) -> [a] -> [a]
```

Applies the callback to each item and returns the original array.
```php
$array = [1, 2, 3, 4];
each(function($item){
    echo $item, PHP_EOL;
}, $array);
// Outputs:
// 1
// 2
// 3
// 4
```

## head

```php
head(array|string $array) : mixed
```

```
[a] -> a
String -> String
```

Returns the first item of the given array or string.
```php
head([1, 2, 3, 4]) // 1
head('Hello') // 'H'
head([]) // null
head('') // ''
```

## last

```php
last(array|string $array) : mixed
```

```
[a] -> a
String -> String
```

Returns the last item of the given array or string.
```php
last([1, 2, 3, 4]) // 4
last('Hello') // 'o'
last([]) // null
last('') // ''
```

## init

```php
init(array|string $array) : array
```

```
[a] -> a
String -> String
```

Returns all but the last element of the given array or string.
```php
init([1, 2, 3, 4]) // [1, 2, 3]
init('Hello') // 'Hell'
init([7]) // []
init([]) // []
init('') // ''
```

## tail

```php
tail(array|string $array) : array
```

```
[a] -> a
String -> String
```

Returns all but the first element of the given array or string.
```php
tail([1, 2, 3, 4]) // [2, 3, 4]
tail('Hello') // 'ello'
tail([7]) // []
tail([]) // []
tail('') // ''
```

## reverse

```php
reverse(array|string $array) : array
```

```
[a] -> [a]
String -> String
```

Alias of `array_reverse()` and `strrev()`.
```php
reverse([1, 2, 3, 4]) // [4, 3, 2, 1]
reverse('Hello') // 'olleH'
```

## length

```php
length(array|string $array) : int
```

```
[a] -> Number
String -> Number
```

Alias for `count()` and `strlen()`.
```php
length([1, 2, 3, 4]) // 4
length('Hello') // 5
```

## all

```php
all(callable $predicate, array $array) : bool
```

```
(a -> Boolean) -> [a] -> Boolean
```

Checks if the `$predicate` is verified by **all** items of the array.
```php
$allNotNull = all(notEq(0));
$allNotNull([9, 3, 2, 4]); // true
$allNotNull([9, 3, 0, 4]); // false
```

## any

```php
any(callable $predicate, array $array) : bool
```

```
(a -> Boolean) -> [a] -> Boolean
```

Checks if the `$predicate` is verified by **any** items of the array.
```php
$anyNumeric = any('is_numeric');
$anyNumeric(['Hello', '12', []]); // true
$anyNumeric(['Hello', 'Foo']); // false
```

## concat

```php
concat(array $array1, array $array2) : array
```

```
[*] -> [*] -> [*]
```

Concatenates two arrays or strings.
```php
concat([1, 2], [3, 4]) // [1, 2, 3, 4]
concat('Hello ', 'World') // 'Hello World'
```

## append

```php
append(mixed $item, array $array) : array
```

```
* -> [*] -> [*]
String -> String -> String
```

Appends an item to an array.
```php
append(5, [1, 2, 3]) // [1, 2, 3, 5]
append(' World', 'Hello') // 'Hello World'
```

## take

```php
take(int $count, array $array) : array
```

```
Number -> [a] -> [a]
Number -> String -> String
```

Takes a number of elements from an array.
```php
$items = ['Foo', 'Bar', 'Baz'];
take(2, $items) // ['Foo', 'Bar']
take(0, $items) // []
take(-2, $items) // []
take(5, 'Hello World') // 'Hello'
take(-5, 'Hello World') // ''
```

## toPairs

```php
toPairs(array $array) : array
```

```
[a => b] -> [(a,b)]
```

Converts an associative array to an array of [key,value] pairs.
```php
$array = ['key' => 'value', 'number' => 53, 'foo', 'bar'];
toPairs($array); // [['key', 'value'], ['number', 53], [0, 'foo'], [1, 'bar']]
```

## chain

```php
chain(callable $fn, array $array) : array
```

```
(a -> [b]) -> [a] -> [b]
```

Applies a function to items of the array and concatenates the results.
This is also known as `flatMap` in some libraries.
```php
$words = chain(split(' '));
$words(['Hello World', 'How are you']) // ['Hello', 'World', 'How', 'are', 'you']
```