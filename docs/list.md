# list

Useful functions to handle lists (arrays having only numeric keys).

- [map](#map) - Curried version of `array_map`.

- [chain](#chain) - Applies a function to items of the array and concatenates the results.

- [filter](#filter) - Curried version of `array_filter` with modified order of arguments.

- [reduce](#reduce) - Curried version of `array_reduce` with modified order of
arguments ($callback, $initial, $list).

- [each](#each) - Applies the callback to each item and returns the original list.

- [head](#head) - Returns the first item of the given array or string.

- [last](#last) - Returns the last item of the given array or string.

- [init](#init) - Returns all but the last element of the given array or string.

- [tail](#tail) - Returns all but the first element of the given array or string.

- [reverse](#reverse) - Alias of `array_reverse()` and `strrev()`.

- [length](#length) - Alias for `count()` and `strlen()`.

- [allSatisfies](#allsatisfies) - Checks if the `$predicate` is verified by **all** items of the array.

- [anySatisfies](#anysatisfies) - Checks if the `$predicate` is verified by **any** item of the array.

- [concat](#concat) - Concatenates two arrays or strings.

- [concatAll](#concatall) - Concatenates a list of arrays or strings.

- [insert](#insert) - Inserts an item at some position into an array or a substring into a string.

- [insertAll](#insertall) - Same as `insert` but inserts an array instead of a single item.

- [append](#append) - Appends an item to an array or a substring to a string.

- [prepend](#prepend) - Inserts an item at the begining of an array or a substring at the begining of a string.

- [take](#take) - Takes a number of elements from an array or a number of characters from a string.

- [takeWhile](#takewhile) - Takes elements from an array while they match the given predicate.

- [takeLastWhile](#takelastwhile) - Same as `takeWhile` but taking elements from the end of the array.

- [takeUntil](#takeuntil) - Takes elements from an array **until** the predicate
is satisfied, not including the satisfying element.

- [takeLastUntil](#takelastuntil) - Same as `takeUntil` but takes elements from the end of the array.

- [remove](#remove) - Removes a number of elements from an array.

- [removeWhile](#removewhile) - Removes elements from an array while they match the given predicate.

- [removeLastWhile](#removelastwhile) - Same as `removeWhile` but removes elements from the end of the array.

- [removeUntil](#removeuntil) - Removes elements from an array **until** the predicate
is satisfied, not removing the satisfying element.

- [removeLastUntil](#removelastuntil) - Same as `removeUntil` but removes elements from the end of the array.

- [fromPairs](#frompairs) - Converts an array of (key, value) pairs to an object (instance of `stdClass`).

- [slices](#slices) - Gets an array of slices of size `$size` from an array.

- [contains](#contains) - Checks if an array contains an item.

- [findIndex](#findindex) - Returns the position/key of the first item satisfying the
predicate in the array or null if no such element is found.

- [findLastIndex](#findlastindex) - Returns the position/key of the last item satisfying the
predicate in the array or null if no such element is found.

- [find](#find) - Returns the first item satisfying the predicate in
the array or null if no such element is found.

- [findLast](#findlast) - Returns the last item satisfying the predicate in
the array or null if no such element is found.

- [indexOf](#indexof) - Returns the index of an item/substring in a list/string.

- [lastIndexOf](#lastindexof) - Same as `indexOf` but returns the key/position/name of the last item/substring/attribute.

- [uniqueBy](#uniqueby) - Removes duplicates from a list.

- [unique](#unique) - Alias of `F\uniqueBy(F\equals())`.

- [groupBy](#groupby) - Converts an array to an associative array, based on the result of calling `$fn`
on each element, and grouping the results according to values returned.

- [pairsFrom](#pairsfrom) - Makes list of pairs from two lists.

- [sort](#sort) - Returns a copy of the given list, ordered using the given comparaison function.

# map

```php
map(callable $fn, array $list) : array
```

```
(a -> b) -> [a] -> [b]
(a -> b) -> {k: a} -> {k: b}
```

Curried version of `array_map`.

```php
$doubles = F\map(function($x) { return 2 * $x; });
$doubles([1, 2, 3, 4]); //=> [2, 4, 6, 8]
```

# chain

```php
chain(callable $fn, array $list) : array
```

```
(a -> [b]) -> [a] -> [b]
```

Applies a function to items of the array and concatenates the results.

This is also known as `flatMap` in some libraries.
```php
$words = F\chain(F\split(' '));
$words(['Hello World', 'How are you']); //=> ['Hello', 'World', 'How', 'are', 'you']
```

# filter

```php
filter(callable $fn, array $list) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Curried version of `array_filter` with modified order of arguments.

The callback is the first argument then the list.
```php
$list = [1, 'aa', 3, [4, 5]];
$numeric = F\filter('is_numeric');
$numeric($list); //=> [1, 3]
```

# reduce

```php
reduce(callable $fn, mixed $initial, array $list) : array
```

```
(* -> a -> *) -> * -> [a] -> *
```

Curried version of `array_reduce` with modified order of
arguments ($callback, $initial, $list).

```php
$list = [1, 2, 3, 4];
$sum = F\reduce('Tarsana\Functional\plus', 0);
$sum($list); //=> 10
```

# each

```php
each(callable $fn, array $list) : array
```

```
(a -> *) -> [a] -> [a]
```

Applies the callback to each item and returns the original list.

```php
$list = [1, 2, 3, 4];
$s = 0;
F\each(function($item) use(&$s){
    $s += $item;
}, $list);

$s; //=> 10
```

# head

```php
head(array|string $list) : mixed
```

```
[a] -> a
String -> String
```

Returns the first item of the given array or string.

```php
F\head([1, 2, 3, 4]); //=> 1
F\head('Hello'); //=> 'H'
F\head([]); //=> null
F\head(''); //=> ''
```

# last

```php
last(array|string $list) : mixed
```

```
[a] -> a
String -> String
```

Returns the last item of the given array or string.

```php
F\last([1, 2, 3, 4]); //=> 4
F\last('Hello'); //=> 'o'
F\last([]); //=> null
F\last(''); //=> ''
```

# init

```php
init(array|string $list) : array
```

```
[a] -> a
String -> String
```

Returns all but the last element of the given array or string.

```php
F\init([1, 2, 3, 4]); //=> [1, 2, 3]
F\init('Hello'); //=> 'Hell'
F\init([7]); //=> []
F\init([]); //=> []
F\init(''); //=> ''
```

# tail

```php
tail(array|string $list) : array
```

```
[a] -> a
String -> String
```

Returns all but the first element of the given array or string.

```php
F\tail([1, 2, 3, 4]); //=> [2, 3, 4]
F\tail('Hello'); //=> 'ello'
F\tail([7]); //=> []
F\tail([]); //=> []
F\tail(''); //=> ''
```

# reverse

```php
reverse(array|string $list) : array
```

```
[a] -> [a]
String -> String
```

Alias of `array_reverse()` and `strrev()`.

```php
F\reverse([1, 2, 3, 4]); //=> [4, 3, 2, 1]
F\reverse('Hello'); //=> 'olleH'
```

# length

```php
length(array|string $list) : int
```

```
[a] -> Number
String -> Number
```

Alias for `count()` and `strlen()`.

```php
F\length([1, 2, 3, 4]); //=> 4
F\length('Hello'); //=> 5
```

# allSatisfies

```php
allSatisfies(callable $predicate, array $list) : bool
```

```
(a -> Boolean) -> [a] -> Boolean
```

Checks if the `$predicate` is verified by **all** items of the array.

```php
$allNotNull = F\allSatisfies(F\pipe(F\eq(0), F\not()));
$allNotNull([9, 3, 2, 4]); //=> true
$allNotNull([9, 3, 0, 4]); //=> false
```

# anySatisfies

```php
anySatisfies(callable $predicate, array $list) : bool
```

```
(a -> Boolean) -> [a] -> Boolean
```

Checks if the `$predicate` is verified by **any** item of the array.

```php
$anyNumeric = F\anySatisfies('is_numeric');
$anyNumeric(['Hello', '12', []]); //=> true
$anyNumeric(['Hello', 'Foo']); //=> false
```

# concat

```php
concat(array $list1, array $list2) : array
```

```
[*] -> [*] -> [*]
String -> String -> String
```

Concatenates two arrays or strings.

```php
F\concat([1, 2], [3, 4]); //=> [1, 2, 3, 4]
F\concat('Hello ', 'World'); //=> 'Hello World'
```

# concatAll

```php
concatAll(array $lists) : array
```

```
[[a]] -> [a]
```

Concatenates a list of arrays or strings.

```php
F\concatAll([[1, 2], [3, 4], [5, 6]]); //=> [1, 2, 3, 4, 5, 6]
F\concatAll(['Hello ', 'World', ' !']); //=> 'Hello World !'
```

# insert

```php
insert(int $position, mixed $item, array $list) : array
```

```
Number -> a -> [a] -> [a]
Number -> String -> String -> String
```

Inserts an item at some position into an array or a substring into a string.

If `$position < 0` the item or substring is inserted before the last `|$position|` elements/characters.
```php
F\insert(2, 'x', [1, 2, 3, 4]); //=> [1, 2, 'x', 3, 4]
F\insert(-1,  'x', [1, 2, 3, 4]); //=> [1, 2, 3, 'x', 4]
F\insert(11, 'x', [1, 2, 3, 4]); //=> [1, 2, 3, 4, 'x']
F\insert(0, 'x', [1, 2, 3, 4]); //=> ['x', 1, 2, 3, 4]
F\insert(-11, 'x', [1, 2, 3, 4]); //=> ['x', 1, 2, 3, 4]
F\insert(32, 'd', 'Hello Worl'); //=> 'Hello World'
F\insert(3, 'l', 'Helo World'); //=> 'Hello World'
F\insert(-7, 'l', 'Helo World'); //=> 'Hello World'
F\insert(0, 'H', 'ello World'); //=> 'Hello World'
F\insert(-70, 'H', 'ello World'); //=> 'Hello World'
```

# insertAll

```php
insertAll(int $position, mixed $items, array $list) : array
```

```
Number -> [a] -> [a] -> [a]
Number -> String -> String -> String
```

Same as `insert` but inserts an array instead of a single item.

```php
F\insertAll(2, ['x', 'y'], [1, 2, 3, 4]); //=> [1, 2, 'x', 'y', 3, 4]
F\insertAll(-1,  ['x', 'y'], [1, 2, 3, 4]); //=> [1, 2, 3, 'x', 'y', 4]
F\insertAll(11, ['x', 'y'], [1, 2, 3, 4]); //=> [1, 2, 3, 4, 'x', 'y']
F\insertAll(0, ['x', 'y'], [1, 2, 3, 4]); //=> ['x', 'y', 1, 2, 3, 4]
F\insertAll(-11, ['x', 'y'], [1, 2, 3, 4]); //=> ['x', 'y', 1, 2, 3, 4]
F\insertAll(2, 'llo', 'He World'); //=> 'Hello World'
```

# append

```php
append(mixed $item, array $list) : array
```

```
* -> [*] -> [*]
String -> String -> String
```

Appends an item to an array or a substring to a string.

```php
F\append(5, [1, 2, 3]); //=> [1, 2, 3, 5]
F\append(' World', 'Hello'); //=> 'Hello World'
```

# prepend

```php
prepend(mixed $item, array $list) : array
```

```
a -> [a] -> [a]
String -> String -> String
```

Inserts an item at the begining of an array or a substring at the begining of a string.

Note that this function is equivalent to `insert(0)`.
```php
F\prepend(5, [1, 2, 3]); //=> [5, 1, 2, 3]
F\prepend('Hello ', 'World'); //=> 'Hello World'
```

# take

```php
take(int $count, array $list) : array
```

```
Number -> [a] -> [a]
Number -> String -> String
```

Takes a number of elements from an array or a number of characters from a string.

If `$count` is negative, the elements are taken from the end of the array/string.
```php
$items = ['Foo', 'Bar', 'Baz'];
F\take(2, $items); //=> ['Foo', 'Bar']
F\take(0, $items); //=> []
F\take(7, $items); //=> ['Foo', 'Bar', 'Baz']
F\take(-2, $items); //=> ['Bar', 'Baz']
F\take(5, 'Hello World'); //=> 'Hello'
F\take(-5, 'Hello World'); //=> 'World'
```

# takeWhile

```php
takeWhile(callable $predicate, array $list) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Takes elements from an array while they match the given predicate.

It stops at the first element not matching the predicate and does not include it in the result.
```php
$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
F\takeWhile(F\startsWith('F'), $items); //=> ['Foo', 'Fun']
F\takeWhile(F\startsWith('D'), $items); //=> []
```

# takeLastWhile

```php
takeLastWhile(callable $predicate, array $list) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Same as `takeWhile` but taking elements from the end of the array.

```php
$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
F\takeLastWhile(F\startsWith('B'), $items); //=> ['Bar', 'Baz']
F\takeLastWhile(F\startsWith('D'), $items); //=> []
```

# takeUntil

```php
takeUntil(callable $predicate, array $list) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Takes elements from an array **until** the predicate
is satisfied, not including the satisfying element.

```php
$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
F\takeUntil(F\startsWith('B'), $items); //=> ['Foo', 'Fun', 'Dev']
F\takeUntil(F\startsWith('F'), $items); //=> []
```

# takeLastUntil

```php
takeLastUntil(callable $predicate, array $list) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Same as `takeUntil` but takes elements from the end of the array.

```php
$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
F\takeLastUntil(F\startsWith('F'), $items); //=> ['Dev', 'Bar', 'Baz']
F\takeLastUntil(F\startsWith('B'), $items); //=> []
```

# remove

```php
remove(int $count, array $list) : array
```

```
Number -> [a] -> [a]
Number -> String -> String
```

Removes a number of elements from an array.

If `$count` is negative, the elements are
removed from the end of the array.
```php
$items = ['Foo', 'Bar', 'Baz'];
F\remove(2, $items); //=> ['Baz']
F\remove(-1, $items); //=> ['Foo', 'Bar']
F\remove(5, $items); //=> []
F\remove(6, 'Hello World'); //=> 'World'
F\remove(-6, 'Hello World'); //=> 'Hello'
```

# removeWhile

```php
removeWhile(callable $predicate, array $list) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Removes elements from an array while they match the given predicate.

It stops at the first element not matching the predicate and does not remove it.
```php
$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
F\removeWhile(F\startsWith('F'), $items); //=> ['Dev', 'Bar', 'Baz']
F\removeWhile(F\startsWith('D'), $items); //=> ['Foo', 'Fun', 'Dev', 'Bar', 'Baz']
```

# removeLastWhile

```php
removeLastWhile(callable $predicate, array $list) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Same as `removeWhile` but removes elements from the end of the array.

```php
$items = ['Foo', 'Fun', 'Bye', 'Dev', 'Bar', 'Baz'];
F\removeLastWhile(F\startsWith('F'), $items); //=> ['Foo', 'Fun', 'Bye', 'Dev', 'Bar', 'Baz']
F\removeLastWhile(F\startsWith('B'), $items); //=> ['Foo', 'Fun', 'Bye', 'Dev']
```

# removeUntil

```php
removeUntil(callable $predicate, array $list) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Removes elements from an array **until** the predicate
is satisfied, not removing the satisfying element.

```php
$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
F\removeUntil(F\startsWith('B'), $items); //=> ['Bar', 'Baz']
F\removeUntil(F\startsWith('F'), $items); //=> ['Foo', 'Fun', 'Dev', 'Bar', 'Baz']
F\removeUntil(F\startsWith('A'), $items); //=> []
```

# removeLastUntil

```php
removeLastUntil(callable $predicate, array $list) : array
```

```
(a -> Boolean) -> [a] -> [a]
```

Same as `removeUntil` but removes elements from the end of the array.

```php
$items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
F\removeLastUntil(F\startsWith('B'), $items); //=> ['Foo', 'Fun', 'Dev', 'Bar', 'Baz']
F\removeLastUntil(F\startsWith('F'), $items); //=> ['Foo', 'Fun']
F\removeLastUntil(F\startsWith('A'), $items); //=> []
```

# fromPairs

```php
fromPairs(array $pairs) : stdClass
```

```
[(k, v)] -> {k: v}
```

Converts an array of (key, value) pairs to an object (instance of `stdClass`).

```php
F\fromPairs([['name', 'Foo'], ['age', 11]]); //=> (object) ['name' => 'Foo', 'age' => 11]
```

# slices

```php
slices(int $size, array $list) : array
```

```
Number -> [a] -> [[a]]
Number -> String -> [String]
```

Gets an array of slices of size `$size` from an array.

```php
$pairs = F\slices(2);
$pairs([1, 2, 3, 4, 5]); //=> [[1, 2], [3, 4], [5]]
$pairs("Hello World"); //=> ['He', 'll', 'o ', 'Wo', 'rl', 'd']
F\slices(5, [1, 2]); //=> [[1, 2]]
F\slices(3, []); //=> []
F\slices(3, ''); //=> ['']
```

# contains

```php
contains(mixed $item, array|string $list) : bool
```

```
a -> [a] -> Boolean
String -> String -> Boolean
```

Checks if an array contains an item.

```php
F\contains('foo', ['foo', 'bar', 'baz']); //=> true
F\contains('hi', ['foo', 'bar', 'baz']); //=> false
F\contains('hi', 'Hello World'); //=> false
F\contains('He', 'Hello World'); //=> true
```

# findIndex

```php
findIndex(callable $predicate, array $list) : mixed
```

```
(a -> Boolean) -> [a] -> Maybe(Number)
(v -> Boolean) -> {k: v} -> Maybe(k)
```

Returns the position/key of the first item satisfying the
predicate in the array or null if no such element is found.

```php
F\findIndex(F\startsWith('b'), ['foo', 'bar', 'baz']); //=> 1
F\findIndex(F\startsWith('b'), ['a' => 'foo', 'b' => 'bar', 'c' => 'baz']); //=> 'b'
F\findIndex(F\startsWith('c'), ['foo', 'bar', 'baz']); //=> null
```

# findLastIndex

```php
findLastIndex(callable $predicate, array $list) : mixed
```

```
(a -> Boolean) -> [a] -> Maybe(Number)
(v -> Boolean) -> {k: v} -> Maybe(k)
```

Returns the position/key of the last item satisfying the
predicate in the array or null if no such element is found.

```php
F\findLastIndex(F\startsWith('b'), ['foo', 'bar', 'baz']); //=> 2
F\findLastIndex(F\startsWith('b'), ['a' => 'foo', 'b' => 'bar', 'c' => 'baz']); //=> 'c'
F\findLastIndex(F\startsWith('c'), ['foo', 'bar', 'baz']); //=> null
```

# find

```php
find(callable $predicate, array $list) : mixed
```

```
(a -> Boolean) -> [a] -> Maybe(a)
```

Returns the first item satisfying the predicate in
the array or null if no such element is found.

```php
F\find(F\startsWith('b'), ['foo', 'bar', 'baz']); //=> 'bar'
F\find(F\startsWith('c'), ['foo', 'bar', 'baz']); //=> null
```

# findLast

```php
findLast(callable $predicate, array $list) : mixed
```

```
(a -> Boolean) -> [a] -> Maybe(a)
```

Returns the last item satisfying the predicate in
the array or null if no such element is found.

```php
F\findLast(F\startsWith('b'), ['foo', 'bar', 'baz']); //=> 'baz'
F\findLast(F\startsWith('c'), ['foo', 'bar', 'baz']); //=> null
```

# indexOf

```php
indexOf(mixed $item, array $list) : int
```

```
a -> [a] -> Number
v -> {k: v} -> Maybe(k)
String -> String -> Number
```

Returns the index of an item/substring in a list/string.

If `$list` is an array, it returns the **key** of the first **item** which is equal to `$item`.
If `$list` is a string, it returns the **position** of the first **substring** which is equal to `$item`.
If `$list` is an object, it returns the **name** of the first **attribute** which is equal to `$item`.
If the searched item, substring or attribute is not found; `null` is returned.
Note that elements are deeply compared using `Tarsana\Functional\equals`.
```php
F\indexOf(['Hello', 'World'], [1, ['Hello', 'World'], true, ['Hello', 'World']]); //=> 1
F\indexOf(['Hello'], [1, ['Hello', 'World'], true]); //=> -1
F\indexOf('World', 'Hello World'); //=> 6
F\indexOf('World !', 'Hello World'); //=> -1
F\indexOf('foo', (object) ['name' => 'foo', 'age' => 11]); //=> 'name'
```

# lastIndexOf

```php
lastIndexOf(mixed $item, array $list) : int
```

```
a -> [a] -> Number
v -> {k: v} -> Maybe(k)
String -> String -> Number
```

Same as `indexOf` but returns the key/position/name of the last item/substring/attribute.

```php
F\lastIndexOf(['Hello', 'World'], [1, ['Hello', 'World'], true, ['Hello', 'World']]); //=> 3
F\lastIndexOf(['Hello'], [1, ['Hello', 'World'], true]); //=> -1
F\lastIndexOf('World', 'Hello World'); //=> 6
F\lastIndexOf('World !', 'Hello World'); //=> -1
F\lastIndexOf('foo', (object) ['name' => 'foo', 'age' => 11]); //=> 'name'
```

# uniqueBy

```php
uniqueBy(callable $areEqual, array $list) : array
```

```
(a -> a -> Boolean) -> [a] -> [a]
```

Removes duplicates from a list.

keeps the first occurence of each group of items which are
equivalent according to the given `$areEqual` callable.
```php
F\uniqueBy(F\eq(), [1, '2', '1', 3, '3', 2, 2]); //=> [1, '2', 3]
```

# unique

```php
unique(array $list) : array
```

```
[a] -> [a]
```

Alias of `F\uniqueBy(F\equals())`.

```php
F\unique([1, '1', [1, 2], 1, ['1', 2], [1, 2]]); //=> [1, '1', [1, 2], ['1', 2]]
```

# groupBy

```php
groupBy(callable $fn, array $list) : array
```

```
(a -> String) -> [a] -> {String: a}
```

Converts an array to an associative array, based on the result of calling `$fn`
on each element, and grouping the results according to values returned.

Note that `$fn` should take an item from the list and return a string.
```php
$persons = [
    ['name' => 'foo', 'age' => 11],
    ['name' => 'bar', 'age' => 9],
    ['name' => 'baz', 'age' => 16],
    ['name' => 'zeta', 'age' => 33],
    ['name' => 'beta', 'age' => 25]
];
$phase = function($person) {
    $age = $person['age'];
    if ($age < 13) return 'child';
    if ($age < 19) return 'teenager';
    return 'adult';
};
F\groupBy($phase, $persons); //=> ['child' => [['name' => 'foo', 'age' => 11], ['name' => 'bar', 'age' => 9]], 'teenager' => [['name' => 'baz', 'age' => 16]], 'adult' => [['name' => 'zeta', 'age' => 33], ['name' => 'beta', 'age' => 25]]]
```

# pairsFrom

```php
pairsFrom(array $list1, array $list2) : array
```

```
[a] -> [b] -> [[a,b]]
```

Makes list of pairs from two lists.

```php
F\pairsFrom([1, 2, 3], ['foo', 'bar', 'baz']); //=> [[1, 'foo'], [2, 'bar'], [3, 'baz']]
F\pairsFrom([1, 2, 3], ['foo', 'bar']); //=> [[1, 'foo'], [2, 'bar']]
F\pairsFrom([1, 3], ['foo', 'bar', 'baz']); //=> [[1, 'foo'], [3, 'bar']]
F\pairsFrom([], ['foo', 'bar', 'baz']); //=> []
```

# sort

```php
sort(callable $compare, array $list) : array
```

```
(a -> a -> Boolean) -> [a] -> [a]
```

Returns a copy of the given list, ordered using the given comparaison function.

The `$compare` function should take two elements from the list and return `true`
if the first element should be placed before the second element in the sorted
list; and return `false` otherwise.

**Note** This function is calling `usort` to sort elements, so:

- if two elements `$a` and `$b` of the list are considered equal
(ie `$compare($a, $b) == false` and `$compare($b, $a) == false`) then their
order in the resulting array is undefined.

- This function assigns new keys to the elements in array. It will remove any
existing keys that may have been assigned, rather than just reordering the keys.
```php
$numbers = [4, 5, 1, 3, 1, 2, 5];
F\sort(F\lt(), $numbers); //=> [1, 1, 2, 3, 4, 5, 5]
F\sort(F\gt(), $numbers); //=> [5, 5, 4, 3, 2, 1, 1]
```

