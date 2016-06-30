# array

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