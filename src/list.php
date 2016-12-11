<?php namespace Tarsana\Functional;
/**
 * Useful functions to handle lists (arrays having only numeric keys).
 * @file
 */

/**
 * Curried version of `array_map`.
 *
 * ```php
 * $doubles = F\map(function($x) { return 2 * $x; });
 * $doubles([1, 2, 3, 4]); //=> [2, 4, 6, 8]
 * ```
 *
 * @signature (a -> b) -> [a] -> [b]
 * @param  callable $fn
 * @param  array $list
 * @return array
 */
function map() {
    static $map = false;
    $map = $map ?: curry(function($fn, $list) {
        return array_map($fn, $list);
    });
    return _apply($map, func_get_args());
}

/**
 * Applies a function to items of the array and concatenates the results.
 *
 * This is also known as `flatMap` in some libraries.
 * ```php
 * $words = F\chain(F\split(' '));
 * $words(['Hello World', 'How are you']); //=> ['Hello', 'World', 'How', 'are', 'you']
 * ```
 *
 * @signature (a -> [b]) -> [a] -> [b]
 * @param  callable $fn
 * @param  array $list
 * @return array
 */
function chain() {
    static $chain = false;
    $chain = $chain ?: curry(function($fn, $list) {
        return concatAll(map($fn, $list));
    });
    return _apply($chain, func_get_args());
}

/**
 * Curried version of `array_filter` with modified order of arguments.
 *
 * The callback is the first argument then the list.
 * ```php
 * $list = [1, 'aa', 3, [4, 5]];
 * $numeric = F\filter('is_numeric');
 * $numeric($list); //=> [1, 3]
 * ```
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $fn
 * @param  array $list
 * @return array
 */
function filter() {
    static $filter = false;
    $filter = $filter ?: curry(function($fn, $list){
        return array_values(array_filter($list, $fn));
    });
    return _apply($filter, func_get_args());
}

/**
 * Curried version of `array_reduce` with modified order of
 * arguments ($callback, $initial, $list).
 *
 * ```php
 * $list = [1, 2, 3, 4];
 * $sum = F\reduce('Tarsana\Functional\plus', 0);
 * $sum($list); //=> 10
 * ```
 *
 * @signature (* -> a -> *) -> * -> [a] -> *
 * @param  callable $fn
 * @param  mixed $initial
 * @param  array $list
 * @return array
 */
function reduce() {
    static $reduce = false;
    $reduce = $reduce ?: curry(function($fn, $initial, $list){
        return array_reduce($list, $fn, $initial);
    });
    return _apply($reduce, func_get_args());
}

/**
 * Applies the callback to each item and returns the original list.
 *
 * ```php
 * $list = [1, 2, 3, 4];
 * $s = 0;
 * F\each(function($item) use(&$s){
 *     $s += $item;
 * }, $list);
 *
 * $s; //=> 10
 * ```
 *
 * @signature (a -> *) -> [a] -> [a]
 * @param  callable $fn
 * @param  array $list
 * @return array
 */
function each() {
    static $each = false;
    $each = $each ?: curry(function($fn, $list){
        foreach ($list as $item) {
            apply($fn, [$item]);
        }
        return $list;
    });
    return _apply($each, func_get_args());
}

/**
 * Returns the first item of the given array or string.
 *
 * ```php
 * F\head([1, 2, 3, 4]); //=> 1
 * F\head('Hello'); //=> 'H'
 * F\head([]); //=> null
 * F\head(''); //=> ''
 * ```
 *
 * @signature [a] -> a
 * @signature String -> String
 * @param  array|string $list
 * @return mixed
 */
function head() {
    static $head = false;
    $head = $head ?: curry(function($list) {
        if(is_string($list))
            return substr($list, 0, 1);
        return (count($list) > 0)
            ? $list[0]
            : null;
    });
    return _apply($head, func_get_args());
}

/**
 * Returns the last item of the given array or string.
 *
 * ```php
 * F\last([1, 2, 3, 4]); //=> 4
 * F\last('Hello'); //=> 'o'
 * F\last([]); //=> null
 * F\last(''); //=> ''
 * ```
 *
 * @signature [a] -> a
 * @signature String -> String
 * @param  array|string $list
 * @return mixed
 */
function last () {
    static $last = false;
    $last = $last ?: curry(function($list) {
        if(is_string($list))
            return substr($list, -1);
        return (count($list) > 0)
            ? $list[count($list) - 1]
            : null;
    });
    return _apply($last, func_get_args());
}

/**
 * Returns all but the last element of the given array or string.
 *
 * ```php
 * F\init([1, 2, 3, 4]); //=> [1, 2, 3]
 * F\init('Hello'); //=> 'Hell'
 * F\init([7]); //=> []
 * F\init([]); //=> []
 * F\init(''); //=> ''
 * ```
 *
 * @signature [a] -> a
 * @signature String -> String
 * @param  array|string $list
 * @return array
 */
function init () {
    static $init = false;
    $init = $init ?: curry(function($list) {
        if(is_string($list))
            return (strlen($list) > 1)
                ? substr($list, 0, strlen($list) - 1)
                : '';
        return (count($list) > 1)
            ? array_slice($list, 0, count($list) - 1)
            : [];
    });
    return _apply($init, func_get_args());
}

/**
 * Returns all but the first element of the given array or string.
 *
 * ```php
 * F\tail([1, 2, 3, 4]); //=> [2, 3, 4]
 * F\tail('Hello'); //=> 'ello'
 * F\tail([7]); //=> []
 * F\tail([]); //=> []
 * F\tail(''); //=> ''
 * ```
 *
 * @signature [a] -> a
 * @signature String -> String
 * @param  array|string $list
 * @return array
 */
function tail () {
    static $tail = false;
    $tail = $tail ?: curry(function($list) {
        if(is_string($list))
            return (strlen($list) > 1)
                ? substr($list, 1)
                : '';
        return (count($list) > 1)
            ? array_slice($list, 1)
            : [];
    });
    return _apply($tail, func_get_args());
}

/**
 * Alias of `array_reverse()` and `strrev()`.
 *
 * ```php
 * F\reverse([1, 2, 3, 4]); //=> [4, 3, 2, 1]
 * F\reverse('Hello'); //=> 'olleH'
 * ```
 *
 * @signature [a] -> [a]
 * @signature String -> String
 * @param  array|string $list
 * @return array
 */
function reverse () {
    static $reverse = false;
    $reverse = $reverse ?: curry(function($list) {
        return is_string($list)
            ? strrev($list)
            : array_reverse($list);
    });
    return _apply($reverse, func_get_args());
}

/**
 * Alias for `count()` and `strlen()`.
 *
 * ```php
 * F\length([1, 2, 3, 4]); //=> 4
 * F\length('Hello'); //=> 5
 * ```
 *
 * @signature [a] -> Number
 * @signature String -> Number
 * @param  array|string $list
 * @return int
 */
function length() {
    static $length = false;
    $length = $length ?: curry(function($list) {
        return is_string($list)
            ? strlen($list)
            : count($list);
    });
    return _apply($length, func_get_args());
}

/**
 * Checks if the `$predicate` is verified by **all** items of the array.
 *
 * ```php
 * $allNotNull = F\allSatisfies(F\pipe(F\eq(0), F\not()));
 * $allNotNull([9, 3, 2, 4]); //=> true
 * $allNotNull([9, 3, 0, 4]); //=> false
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> Boolean
 * @param  callable $predicate
 * @param  array $list
 * @return bool
 */
function allSatisfies() {
    static $allSatisfies = false;
    $allSatisfies = $allSatisfies ?: curry(function($predicate, $list) {
        return length(filter($predicate, $list)) == length($list);
    });
    return _apply($allSatisfies, func_get_args());
}


/**
 * Checks if the `$predicate` is verified by **any** item of the array.
 *
 * ```php
 * $anyNumeric = F\anySatisfies('is_numeric');
 * $anyNumeric(['Hello', '12', []]); //=> true
 * $anyNumeric(['Hello', 'Foo']); //=> false
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> Boolean
 * @param  callable $predicate
 * @param  array $list
 * @return bool
 */
function anySatisfies() {
    static $anySatisfies = false;
    $anySatisfies = $anySatisfies ?: curry(function($predicate, $list) {
        return null != findIndex($predicate, $list);
    });
    return _apply($anySatisfies, func_get_args());
}

/**
 * Concatenates two arrays or strings.
 *
 * ```php
 * F\concat([1, 2], [3, 4]); //=> [1, 2, 3, 4]
 * F\concat('Hello ', 'World'); //=> 'Hello World'
 * ```
 *
 * @signature [*] -> [*] -> [*]
 * @signature String -> String -> String
 * @param  array $list1
 * @param  array $list2
 * @return array
 */
function concat() {
    static $concat = false;
    $concat = $concat ?: curry(function($list1, $list2) {
        $t1 = toString($list1);
        $t2 = toString($list2);
        if (is_string($list1) && is_string($list2))
            return $list1 . $list2;
        return array_merge($list1, $list2);
    });
    return _apply($concat, func_get_args());
}

/**
 * Concatenates a list of arrays or strings.
 *
 * ```php
 * F\concatAll([[1, 2], [3, 4], [5, 6]]); //=> [1, 2, 3, 4, 5, 6]
 * F\concatAll(['Hello ', 'World', ' !']); //=> 'Hello World !'
 * ```
 *
 * @signature [[a]] -> [a]
 * @param  array $lists
 * @return array
 */
function concatAll() {
    static $concatAll = false;
    $concatAll = $concatAll ?: curry(function($lists) {
        return length($lists) == 0 ? [] :
            reduce(concat(), head($lists), tail($lists));
    });
    return _apply($concatAll, func_get_args());
}

/**
 * Inserts an item at some position into an array or a substring into a string.
 *
 * If `$position < 0` the item or substring is inserted before the last `|$position|` elements/characters.
 * ```php
 * F\insert(2, 'x', [1, 2, 3, 4]); //=> [1, 2, 'x', 3, 4]
 * F\insert(-1,  'x', [1, 2, 3, 4]); //=> [1, 2, 3, 'x', 4]
 * F\insert(11, 'x', [1, 2, 3, 4]); //=> [1, 2, 3, 4, 'x']
 * F\insert(0, 'x', [1, 2, 3, 4]); //=> ['x', 1, 2, 3, 4]
 * F\insert(-11, 'x', [1, 2, 3, 4]); //=> ['x', 1, 2, 3, 4]
 * F\insert(3, 'l', 'Helo World'); //=> 'Hello World'
 * ```
 *
 * @signature Number -> a -> [a] -> [a]
 * @signature Number -> String -> String -> String
 * @param  int $position
 * @param  mixed $item
 * @param  array $list
 * @return array
 */
function insert() {
    static $insert = false;
    $insert = $insert ?: curry(function($position, $item, $list) {
        return (is_string($list))
            ? insertAll($position, $item, $list)
            : insertAll($position, [$item], $list);
    });
    return _apply($insert, func_get_args());
}

/**
 * Same as `insert` but inserts an array instead of a single item.
 *
 * ```php
 * F\insertAll(2, ['x', 'y'], [1, 2, 3, 4]); //=> [1, 2, 'x', 'y', 3, 4]
 * F\insertAll(-1,  ['x', 'y'], [1, 2, 3, 4]); //=> [1, 2, 3, 'x', 'y', 4]
 * F\insertAll(11, ['x', 'y'], [1, 2, 3, 4]); //=> [1, 2, 3, 4, 'x', 'y']
 * F\insertAll(0, ['x', 'y'], [1, 2, 3, 4]); //=> ['x', 'y', 1, 2, 3, 4]
 * F\insertAll(-11, ['x', 'y'], [1, 2, 3, 4]); //=> ['x', 'y', 1, 2, 3, 4]
 * F\insertAll(2, 'llo', 'He World'); //=> 'Hello World'
 * ```
 *
 * @signature Number -> [a] -> [a] -> [a]
 * @signature Number -> String -> String -> String
 * @param  int $position
 * @param  mixed $items
 * @param  array $list
 * @return array
 */
function insertAll() {
    static $insertAll = false;
    $insertAll = $insertAll ?: curry(function($position, $items, $list) {
        $length = length($list);
        if ($position < 0)
            $position = $length + $position;
        if ($position < 0)
            $position = 0;
        return ($position >= $length)
            ? concat($list, $items)
            : concatAll([take($position, $list), $items, remove($position, $list)]);
    });
    return _apply($insertAll, func_get_args());
}

/**
 * Appends an item to an array or a substring to a string.
 *
 * ```php
 * F\append(5, [1, 2, 3]); //=> [1, 2, 3, 5]
 * F\append(' World', 'Hello'); //=> 'Hello World'
 * ```
 *
 * @signature * -> [*] -> [*]
 * @signature String -> String -> String
 * @param  mixed $item
 * @param  array $list
 * @return array
 */
function append() {
    static $append = false;
    $append = $append ?: curry(function ($item, $list) {
        return insert(length($list), $item, $list);
    });
    return _apply($append, func_get_args());
}

/**
 * Inserts an item at the begining of an array or a substring at the begining of a string.
 *
 * Note that this function is equivalent to `insert(0)`.
 * ```php
 * F\prepend(5, [1, 2, 3]); //=> [5, 1, 2, 3]
 * F\prepend('Hello ', 'World'); //=> 'Hello World'
 * ```
 *
 * @signature a -> [a] -> [a]
 * @signature String -> String -> String
 * @param  mixed $item
 * @param  array $list
 * @return array
 */
function prepend() {
    return _apply(insert(0), func_get_args());
}

/**
 * Takes a number of elements from an array.
 *
 * If `$count` is negative, the elements are taken from the end of the array.
 * ```php
 * $items = ['Foo', 'Bar', 'Baz'];
 * F\take(2, $items); //=> ['Foo', 'Bar']
 * F\take(0, $items); //=> []
 * F\take(-2, $items); //=> ['Bar', 'Baz']
 * F\take(5, 'Hello World'); //=> 'Hello'
 * F\take(-5, 'Hello World'); //=> 'World'
 * ```
 *
 * @signature Number -> [a] -> [a]
 * @signature Number -> String -> String
 * @param  int $count
 * @param  array $list
 * @return array
 */
function take() {
    static $take = false;
    $take = $take ?: curry(function($count, $list) {
        $length = length($list);
        if ($count > $length || $count < -$length)
            return [];
        if(is_string($list)) {
            return ($count >= 0)
                ? substr($list, 0, $count)
                : substr($list, $count);
        }
        return ($count >= 0)
            ? array_slice($list, 0, $count)
            : array_slice($list, $count);
    });
    return _apply($take, func_get_args());
}

/**
 * Takes elements from an array while they match the given predicate.
 *
 * It stops at the first element not matching the predicate and does not include it in the result.
 * ```php
 * $items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
 * F\takeWhile(F\startsWith('F'), $items); //=> ['Foo', 'Fun']
 * F\takeWhile(F\startsWith('D'), $items); //=> []
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $predicate
 * @param  array $list
 * @return array
 */
function takeWhile() {
    static $takeWhile = false;
    $takeWhile = $takeWhile ?: curry(function($predicate, $list) {
        $first = head($list);
        return (null == $first || !$predicate($first))
            ? []
            : prepend($first, takeWhile($predicate, tail($list)));

    });
    return _apply($takeWhile, func_get_args());
}

/**
 * Same as `takeWhile` but taking elements from the end of the array.
 *
 * ```php
 * $items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
 * F\takeLastWhile(F\startsWith('B'), $items); //=> ['Bar', 'Baz']
 * F\takeLastWhile(F\startsWith('D'), $items); //=> []
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $predicate
 * @param  array $list
 * @return array
 */
function takeLastWhile() {
    static $takeLastWhile = false;
    $takeLastWhile = $takeLastWhile ?: curry(function($predicate, $list) {
        $last = last($list);
        return (null == $last || !$predicate($last))
            ? []
            : append($last, takeLastWhile($predicate, init($list)));

    });
    return _apply($takeLastWhile, func_get_args());
}

/**
 * Takes elements from an array **until** the predicate
 * is satisfied, not including the satisfying element.
 *
 * ```php
 * $items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
 * F\takeUntil(F\startsWith('B'), $items); //=> ['Foo', 'Fun', 'Dev']
 * F\takeUntil(F\startsWith('F'), $items); //=> []
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $predicate
 * @param  array $list
 * @return array
 */
function takeUntil() {
    static $takeUntil = false;
    $takeUntil = $takeUntil ?: curry(function($predicate, $list) {
        return takeWhile(pipe($predicate, not()), $list);
    });
    return _apply($takeUntil, func_get_args());
}

/**
 * Same as `takeUntil` but takes elements from the end of the array.
 *
 * ```php
 * $items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
 * F\takeLastUntil(F\startsWith('F'), $items); //=> ['Dev', 'Bar', 'Baz']
 * F\takeLastUntil(F\startsWith('B'), $items); //=> []
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $predicate
 * @param  array $list
 * @return array
 */
function takeLastUntil() {
    static $takeLastUntil = false;
    $takeLastUntil = $takeLastUntil ?: curry(function($predicate, $list) {
        return takeLastWhile(pipe($predicate, not()), $list);
    });
    return _apply($takeLastUntil, func_get_args());
}

/**
 * Removes a number of elements from an array.
 *
 * If `$count` is negative, the elements are
 * removed from the end of the array.
 * ```php
 * $items = ['Foo', 'Bar', 'Baz'];
 * F\remove(2, $items); //=> ['Baz']
 * F\remove(-1, $items); //=> ['Foo', 'Bar']
 * F\remove(5, $items); //=> []
 * F\remove(6, 'Hello World'); //=> 'World'
 * F\remove(-6, 'Hello World'); //=> 'Hello'
 * ```
 *
 * @signature Number -> [a] -> [a]
 * @signature Number -> String -> String
 * @param  int $count
 * @param  array $list
 * @return array
 */
function remove() {
    static $remove = false;
    $remove = $remove ?: curry(function($count, $list) {
        $length = length($list);
        if ($count > $length || $count < -$length)
            return [];
        return ($count > 0)
            ? take($count - $length, $list)
            : take($count + $length, $list);
    });
    return _apply($remove, func_get_args());
}

/**
 * Removes elements from an array while they match the given predicate.
 *
 * It stops at the first element not matching the predicate and does not remove it.
 * ```php
 * $items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
 * F\removeWhile(F\startsWith('F'), $items); //=> ['Dev', 'Bar', 'Baz']
 * F\removeWhile(F\startsWith('D'), $items); //=> ['Foo', 'Fun', 'Dev', 'Bar', 'Baz']
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $predicate
 * @param  array $list
 * @return array
 */
function removeWhile() {
    static $removeWhile = false;
    $removeWhile = $removeWhile ?: curry(function($predicate, $list) {
        return remove(length(takeWhile($predicate, $list)), $list);
    });
    return _apply($removeWhile, func_get_args());
}

/**
 * Same as `removeWhile` but removes elements from the end of the array.
 *
 * ```php
 * $items = ['Foo', 'Fun', 'Bye', 'Dev', 'Bar', 'Baz'];
 * F\removeLastWhile(F\startsWith('F'), $items); //=> ['Foo', 'Fun', 'Bye', 'Dev', 'Bar', 'Baz']
 * F\removeLastWhile(F\startsWith('B'), $items); //=> ['Foo', 'Fun', 'Bye', 'Dev']
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $predicate
 * @param  array $list
 * @return array
 */
function removeLastWhile() {
    static $removeLastWhile = false;
    $removeLastWhile = $removeLastWhile ?: curry(function($predicate, $list) {
        return remove(- length(takeLastWhile($predicate, $list)), $list);
    });
    return _apply($removeLastWhile, func_get_args());
}

/**
 * Removes elements from an array **until** the predicate
 * is satisfied, not removing the satisfying element.
 *
 * ```php
 * $items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
 * F\removeUntil(F\startsWith('B'), $items); //=> ['Bar', 'Baz']
 * F\removeUntil(F\startsWith('F'), $items); //=> ['Foo', 'Fun', 'Dev', 'Bar', 'Baz']
 * F\removeUntil(F\startsWith('A'), $items); //=> []
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $predicate
 * @param  array $list
 * @return array
 */
function removeUntil() {
    static $removeUntil = false;
    $removeUntil = $removeUntil ?: curry(function($predicate, $list) {
        return remove(length(takeUntil($predicate, $list)), $list);
    });
    return _apply($removeUntil, func_get_args());
}

/**
 * Same as `removeUntil` but removes elements from the end of the array.
 *
 * ```php
 * $items = ['Foo', 'Fun', 'Dev', 'Bar', 'Baz'];
 * F\removeLastUntil(F\startsWith('B'), $items); //=> ['Foo', 'Fun', 'Dev', 'Bar', 'Baz']
 * F\removeLastUntil(F\startsWith('F'), $items); //=> ['Foo', 'Fun']
 * F\removeLastUntil(F\startsWith('A'), $items); //=> []
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $predicate
 * @param  array $list
 * @return array
 */
function removeLastUntil() {
    static $removeLastUntil = false;
    $removeLastUntil = $removeLastUntil ?: curry(function($predicate, $list) {
        return remove(- length(takeLastUntil($predicate, $list)), $list);
    });
    return _apply($removeLastUntil, func_get_args());
}

/**
 * Converts an array of (key, value) pairs to an object (instance of `stdClass`).
 *
 * ```php
 * F\fromPairs([['name', 'Foo'], ['age', 11]]); //=> (object) ['name' => 'Foo', 'age' => 11]
 * ```
 *
 * @signature [(k, v)] -> {k: v}
 * @param  array $pairs
 * @return stdClass
 */
function fromPairs() {
    static $fromPairs = false;
    $fromPairs = $fromPairs ?: curry(function($pairs) {
        return reduce(function($result, $pair) {
            $result->{$pair[0]} = $pair[1];
            return $result;
        }, new \stdClass, $pairs);
    });
    return _apply($fromPairs, func_get_args());
}

/**
 * Gets an array of slices of size `$size` from an array.
 *
 * ```php
 * $pairs = F\slices(2);
 * $pairs([1, 2, 3, 4, 5]); //=> [[1, 2], [3, 4], [5]]
 * $pairs("Hello World"); //=> ['He', 'll', 'o ', 'Wo', 'rl', 'd']
 * F\slices(5, [1, 2]); //=> [[1, 2]]
 * F\slices(3, []); //=> []
 * F\slices(3, ''); //=> ''
 * ```
 *
 * @signature Number -> [a] -> [[a]]
 * @signature Number -> String -> [String]
 * @param  int $size
 * @param  array $list
 * @return array
 */
function slices() {
    static $slices = false;
    $slices = $slices ?: curry(function($size, $list) {
        if(empty($list))
            return is_string($list) ? '' : [];
        if(length($list) <= $size)
            return [$list];
        return prepend(take($size, $list), slices($size, remove($size, $list)));
    });
    return _apply($slices, func_get_args());
}

/**
 * Checks if an array contains an item.
 *
 * ```php
 * F\contains('foo', ['foo', 'bar', 'baz']); //=> true
 * F\contains('hi', ['foo', 'bar', 'baz']); //=> false
 * F\contains('hi', 'Hello World'); //=> false
 * F\contains('He', 'Hello World'); //=> true
 * ```
 *
 * @signature a -> [a] -> Boolean
 * @signature String -> String -> Boolean
 * @param  mixed $item
 * @param  array|string $list
 * @return bool
 */
function contains() {
    static $contains = false;
    $contains = $contains ?: curry(function($item, $list) {
        return -1 != indexOf($item, $list);
    });
    return _apply($contains, func_get_args());
}

/**
 * Returns the position/key of the first item satisfying the
 * predicate in the array or null if no such element is found.
 *
 * ```php
 * F\findIndex(F\startsWith('b'), ['foo', 'bar', 'baz']); //=> 1
 * F\findIndex(F\startsWith('b'), ['a' => 'foo', 'b' => 'bar', 'c' => 'baz']); //=> 'b'
 * F\findIndex(F\startsWith('c'), ['foo', 'bar', 'baz']); //=> null
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> Maybe(Number)
 * @signature (v -> Boolean) -> {k: v} -> Maybe(k)
 * @param  callable $predicate
 * @param  array $list
 * @return mixed
 */
function findIndex() {
    static $findIndex = false;
    $findIndex = $findIndex ?: curry(function($predicate, $list) {
        foreach ($list as $key => $value) {
            if ($predicate($value))
                return $key;
        }
        return null;
    });
    return _apply($findIndex, func_get_args());
}

/**
 * Returns the position/key of the last item satisfying the
 * predicate in the array or null if no such element is found.
 *
 * ```php
 * F\findLastIndex(F\startsWith('b'), ['foo', 'bar', 'baz']); //=> 2
 * F\findLastIndex(F\startsWith('b'), ['a' => 'foo', 'b' => 'bar', 'c' => 'baz']); //=> 'c'
 * F\findLastIndex(F\startsWith('c'), ['foo', 'bar', 'baz']); //=> null
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> Maybe(Number)
 * @signature (v -> Boolean) -> {k: v} -> Maybe(k)
 * @param  callable $predicate
 * @param  array $list
 * @return mixed
 */
function findLastIndex() {
    static $findLastIndex = false;
    $findLastIndex = $findLastIndex ?: curry(function($predicate, $list) {
        foreach (reverse(toPairs($list)) as $pair) {
            if($predicate($pair[1]))
                return $pair[0];
        }
        return null;
    });
    return _apply($findLastIndex, func_get_args());
}

/**
 * Returns the first item satisfying the predicate in
 * the array or null if no such element is found.
 *
 * ```php
 * F\find(F\startsWith('b'), ['foo', 'bar', 'baz']); //=> 'bar'
 * F\find(F\startsWith('c'), ['foo', 'bar', 'baz']); //=> null
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> Maybe(a)
 * @param  callable $predicate
 * @param  array $list
 * @return mixed
 */
function find() {
    static $find = false;
    $find = $find ?: curry(function($predicate, $list) {
        return get(findIndex($predicate, $list), $list);
    });
    return _apply($find, func_get_args());
}

/**
 * Returns the last item satisfying the predicate in
 * the array or null if no such element is found.
 *
 * ```php
 * F\findLast(F\startsWith('b'), ['foo', 'bar', 'baz']); //=> 'baz'
 * F\findLast(F\startsWith('c'), ['foo', 'bar', 'baz']); //=> null
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> Maybe(a)
 * @param  callable $predicate
 * @param  array $list
 * @return mixed
 */
function findLast() {
    static $findLast = false;
    $findLast = $findLast ?: curry(function($predicate, $list) {
        return get(findLastIndex($predicate, $list), $list);
    });
    return _apply($findLast, func_get_args());
}

/**
 * Returns the index of an item/substring in a list/string.
 *
 * If `$list` is an array, it returns the **key** of the first **item** which is equal to `$item`.
 * If `$list` is a string, it returns the **position** of the first **substring** which is equal to `$item`.
 * If `$list` is an object, it returns the **name** of the first **attribute** which is equal to `$item`.
 * If the searched item, substring or attribute is not found; `null` is returned.
 * Note that elements are deeply compared using `Tarsana\Functional\equals`.
 * ```php
 * F\indexOf(['Hello', 'World'], [1, ['Hello', 'World'], true, ['Hello', 'World']]); //=> 1
 * F\indexOf(['Hello'], [1, ['Hello', 'World'], true]); //=> -1
 * F\indexOf('World', 'Hello World'); //=> 6
 * F\indexOf('World !', 'Hello World'); //=> -1
 * F\indexOf('foo', (object) ['name' => 'foo', 'age' => 11]); //=> 'name'
 * ```
 *
 * @signature a -> [a] -> Number
 * @signature v -> {k: v} -> Maybe(k)
 * @signature String -> String -> Number
 * @param  mixed $item
 * @param  array $list
 * @return int
 */
function indexOf() {
    static $indexOf = false;
    $indexOf = $indexOf ?: curry(function($item, $list) {
        if (is_string($list)) {
            $index = strpos($list, $item);
        } else {
            $index = findIndex(equals($item), $list);
        }
        return (false === $index || null === $index)
            ? -1
            : $index;
    });
    return _apply($indexOf, func_get_args());
}

/**
 * Same as `indexOf` but returns the key/position/name of the last item/substring/attribute.
 *
 * ```php
 * F\lastIndexOf(['Hello', 'World'], [1, ['Hello', 'World'], true, ['Hello', 'World']]); //=> 3
 * F\lastIndexOf(['Hello'], [1, ['Hello', 'World'], true]); //=> -1
 * F\lastIndexOf('World', 'Hello World'); //=> 6
 * F\lastIndexOf('World !', 'Hello World'); //=> -1
 * F\lastIndexOf('foo', (object) ['name' => 'foo', 'age' => 11]); //=> 'name'
 * ```
 *
 * @signature a -> [a] -> Number
 * @signature String -> String -> Number
 * @param  mixed $item
 * @param  array $list
 * @return int
 */
function lastIndexOf() {
    static $lastIndexOf = false;
    $lastIndexOf = $lastIndexOf ?: curry(function($item, $list) {
        if (is_string($list)) {
            $index = strrpos($list, $item);
        } else {
            $index = findLastIndex(equals($item), $list);
        }
        return (false === $index || null === $index)
            ? -1
            : $index;
    });
    return _apply($lastIndexOf, func_get_args());
}

/**
 * Removes duplicates from a list.
 *
 * keeps the first occurence of each group of items which are
 * equivalent according to the given `$areEqual` callable.
 * ```php
 * F\uniqueBy(F\eq(), [1, '2', '1', 3, '3', 2, 2]); //=> [1, '2', 3]
 * ```
 *
 * @signature (a -> a -> Boolean) -> [a] -> [a]
 * @param  callable $areEqual
 * @param  array $list
 * @return array
 */
function uniqueBy() {
    static $uniqueBy = false;
    $uniqueBy = $uniqueBy ?: curry(function($areEqual, $list) {
        // make sure the compare function is curried
        $compare = function($a) use($areEqual) {
            return function($b) use($areEqual, $a) {
                return $areEqual($a, $b);
            };
        };

        return reduce(function($result, $item) use($compare) {
            return (null === findIndex($compare($item), $result))
                ? append($item, $result)
                : $result;
        }, [], $list);
    });
    return _apply($uniqueBy, func_get_args());
}

/**
 * Alias of `F\uniqueBy(F\equals())`.
 *
 * ```php
 * F\unique([1, '1', [1, 2], 1, ['1', 2], [1, 2]]); //=> [1, '1', [1, 2], ['1', 2]]
 * ```
 *
 * @signature [a] -> [a]
 * @param  array $list
 * @return array
 */
function unique() {
    return _apply(uniqueBy(equals()), func_get_args());
}

/**
 * Converts an array to an associative array, based on the result of calling `$fn`
 * on each element, and grouping the results according to values returned.
 *
 * Note that `$fn` should take an item from the list and return a string.
 * ```php
 * $persons = [
 *     ['name' => 'foo', 'age' => 11],
 *     ['name' => 'bar', 'age' => 9],
 *     ['name' => 'baz', 'age' => 16],
 *     ['name' => 'zeta', 'age' => 33],
 *     ['name' => 'beta', 'age' => 25]
 * ];
 * $phase = function($person) {
 *     $age = $person['age'];
 *     if ($age < 13) return 'child';
 *     if ($age < 19) return 'teenager';
 *     return 'adult';
 * };
 * F\groupBy($phase, $persons); //=> ['child' => [['name' => 'foo', 'age' => 11], ['name' => 'bar', 'age' => 9]], 'teenager' => [['name' => 'baz', 'age' => 16]], 'adult' => [['name' => 'zeta', 'age' => 33], ['name' => 'beta', 'age' => 25]]]
 * ```
 *
 * @signature (a -> String) -> [a] -> {String: a}
 * @param  callable $fn
 * @param  array $list
 * @return array
 */
function groupBy() {
    static $groupBy = false;
    $groupBy = $groupBy ?: curry(function($fn, $list) {
        return reduce(function($result, $item) use($fn) {
            $index = $fn($item);
            if (! isset($result[$index]))
                $result[$index] = [];
            $result[$index][] = $item;
            return $result;
        }, [], $list);
    });
    return _apply($groupBy, func_get_args());
}
