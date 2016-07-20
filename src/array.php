<?php namespace Tarsana\Functional;
/**
 * This file contains some useful functions to handle arrays.
 */

/**
 * Gets the value of a key.
 * ```php
 * $data = [
 *     ['name' => 'foo', 'type' => 'test'],
 *     ['name' => 'bar', 'type' => 'test']
 * ];
 * $nameOf = value('name');
 * value(0, $data) // ['name' => 'foo', 'type' => 'test']
 * $nameOf($data[1]) // 'bar'
 * ```
 *
 * @signature String -> [key => *] -> *
 * @param  string $name
 * @param  array $array
 * @return mixed
 */
function value() {
    $value = function($name, $array){
        return $array[$name];
    };
    return apply(curry($value), func_get_args());
}

/**
 * Curried version of `array_map()`.
 * ```php
 * $doubles = map(function($x) { return 2 * $x; });
 * $doubles([1, 2, 3, 4]) // [2, 4, 6, 8]
 * ```
 *
 * @signature (a -> b) -> [a] -> [b]
 * @param  callable $fn
 * @param  array $array
 * @return array
 */
function map() {
    return apply(curry(function($fn, $array){
        return array_map($fn, $array);
    }), func_get_args());
}

/**
 * Curried version of `array_filter` with modified order of
 * arguments. The callback is the first argument then the array.
 * ```php
 * $array = [1, 'aa', 3, [4, 5]];
 * $numeric = F\filter('is_numeric');
 * $numeric($array) // [1, 3]
 * ```
 * @signature (a -> Boolean) -> [a] -> [a]
 * @param  callable $fn
 * @param  array $array
 * @return array
 */
function filter() {
    $filter = function($fn, $array){
        return array_values(array_filter($array, $fn));
    };
    return apply(curry($filter), func_get_args());
}

/**
 * Curried version of `array_reduce` with modified order of
 * arguments ($callback, $initial, $array).
 * ```php
 * $array = [1, 2, 3, 4];
 * $sum = reduce('Tarsana\Functional\plus', 0);
 * $sum($array) // 10
 * ```
 *
 * @signature (* -> a -> *) -> * -> [a] -> *
 * @param  callable $fn
 * @param  mixed $initial
 * @param  array $array
 * @return array
 */
function reduce() {
    $reduce = function($fn, $initial, $array){
        return array_reduce($array, $fn, $initial);
    };
    return apply(curry($reduce), func_get_args());
}

/**
 * Applies the callback to each item and returns the original array.
 * ```php
 * $array = [1, 2, 3, 4];
 * each(function($item){
 *     echo $item, PHP_EOL;
 * }, $array);
 * // Outputs:
 * // 1
 * // 2
 * // 3
 * // 4
 * ```
 *
 * @signature (a -> *) -> [a] -> [a]
 * @param  callable $fn
 * @param  array $array
 * @return array
 */
function each() {
    $each = function($fn, $array){
        foreach ($array as $item) {
            apply($fn, [$item]);
        }
        return $array;
    };
    return apply(curry($each), func_get_args());
}

/**
 * Returns the first item of the given array or string.
 * ```php
 * head([1, 2, 3, 4]) // 1
 * head('Hello') // 'H'
 * head([]) // null
 * head('') // ''
 * ```
 *
 * @signature [a] -> a
 * @signature String -> String
 * @param  array|string $array
 * @return mixed
 */
function head($array) {
    if(is_string($array))
        return substr($array, 0, 1);
    return (count($array) > 0)
        ? $array[0]
        : null;
}

/**
 * Returns the last item of the given array or string.
 * ```php
 * last([1, 2, 3, 4]) // 4
 * last('Hello') // 'o'
 * last([]) // null
 * last('') // ''
 * ```
 *
 * @signature [a] -> a
 * @signature String -> String
 * @param  array|string $array
 * @return mixed
 */
function last($array) {
    if(is_string($array))
        return substr($array, -1);
    return (count($array) > 0)
        ? $array[count($array) - 1]
        : null;
}

/**
 * Returns all but the last element of the given array or string.
 * ```php
 * init([1, 2, 3, 4]) // [1, 2, 3]
 * init('Hello') // 'Hell'
 * init([7]) // []
 * init([]) // []
 * init('') // ''
 * ```
 *
 * @signature [a] -> a
 * @signature String -> String
 * @param  array|string $array
 * @return array
 */
function init($array) {
    if(is_string($array))
        return (strlen($array) > 1)
            ? substr($array, 0, strlen($array) - 1)
            : '';
    return (count($array) > 1)
        ? array_slice($array, 0, count($array) - 1)
        : [];
}

/**
 * Returns all but the first element of the given array or string.
 * ```php
 * tail([1, 2, 3, 4]) // [2, 3, 4]
 * tail('Hello') // 'ello'
 * tail([7]) // []
 * tail([]) // []
 * tail('') // ''
 * ```
 *
 * @signature [a] -> a
 * @signature String -> String
 * @param  array|string $array
 * @return array
 */
function tail($array) {
    if(is_string($array))
        return (strlen($array) > 1)
            ? substr($array, 1)
            : '';
    return (count($array) > 1)
        ? array_slice($array, 1)
        : [];
}

/**
 * Alias of `array_reverse()` and `strrev()`.
 * ```php
 * reverse([1, 2, 3, 4]) // [4, 3, 2, 1]
 * reverse('Hello') // 'olleH'
 * ```
 *
 * @signature [a] -> [a]
 * @signature String -> String
 * @param  array|string $array
 * @return array
 */
function reverse($array) {
    return is_string($array)
        ? strrev($array)
        : array_reverse($array);
}

/**
 * Alias for `count()` and `strlen()`.
 * ```php
 * length([1, 2, 3, 4]) // 4
 * length('Hello') // 5
 * ```
 *
 * @signature [a] -> Number
 * @signature String -> Number
 * @param  array|string $array
 * @return int
 */
function length($array) {
    return is_string($array)
        ? strlen($array)
        : count($array);
}

/**
 * Checks if the `$predicate` is verified by **all** items of the array.
 * ```php
 * $allNotNull = all(notEq(0));
 * $allNotNull([9, 3, 2, 4]); // true
 * $allNotNull([9, 3, 0, 4]); // false
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> Boolean
 * @param  callable $predicate
 * @param  array $array
 * @return bool
 */
function all() {
    $all = function($predicate, $array) {
        return length(filter($predicate, $array)) == length($array);
    };
    return apply(curry($all), func_get_args());
}


/**
 * Checks if the `$predicate` is verified by **any** items of the array.
 * ```php
 * $anyNumeric = any('is_numeric');
 * $anyNumeric(['Hello', '12', []]); // true
 * $anyNumeric(['Hello', 'Foo']); // false
 * ```
 *
 * @signature (a -> Boolean) -> [a] -> Boolean
 * @param  callable $predicate
 * @param  array $array
 * @return bool
 */
function any() {
    // TODO: use findBy when available instead of filter !
    $any = function($predicate, $array) {
        return length(filter($predicate, $array)) > 0;
    };
    return apply(curry($any), func_get_args());
}

/**
 * Concatenates two arrays or strings.
 * ```php
 * concat([1, 2], [3, 4]) // [1, 2, 3, 4]
 * concat('Hello ', 'World') // 'Hello World'
 * ```
 *
 * @signature [*] -> [*] -> [*]
 * @param  array $array1
 * @param  array $array2
 * @return array
 */
function concat() {
    $concat = function($array1, $array2) {
        if (is_string($array1))
            return $array1 . $array2;
        return array_merge($array1, $array2);
    };
    return apply(curry($concat), func_get_args());
}

/**
 * Appends an item to an array.
 * ```php
 * append(5, [1, 2, 3]) // [1, 2, 3, 5]
 * append(' World', 'Hello') // 'Hello World'
 * ```
 *
 * @signature * -> [*] -> [*]
 * @signature String -> String -> String
 * @param  mixed $item
 * @param  array $array
 * @return array
 */
function append() {
    $append = function ($item, $array) {
        if (is_string($array))
            return $array . $item;
        return array_merge($array, [$item]);
    };
    return apply(curry($append), func_get_args());
}

/**
 * Adds an item to teh first of an array.
 * ```php
 * prepend(5, [1, 2, 3]) // [5, 1, 2, 3]
 * prepend('Hello ', 'World') // 'Hello World'
 * ```
 *
 * @signature * -> [*] -> [*]
 * @signature String -> String -> String
 * @param  mixed $item
 * @param  array $array
 * @return array
 */
function prepend() {
    $prepend = function ($item, $array) {
        if (is_string($array))
            return $item . $array;
        return array_merge([$item], $array);
    };
    return apply(curry($prepend), func_get_args());
}

/**
 * Takes a number of elements from an array.
 * ```php
 * $items = ['Foo', 'Bar', 'Baz'];
 * take(2, $items) // ['Foo', 'Bar']
 * take(0, $items) // []
 * take(-2, $items) // []
 * take(5, 'Hello World') // 'Hello'
 * take(-5, 'Hello World') // ''
 * ```
 *
 * @signature Number -> [a] -> [a]
 * @signature Number -> String -> String
 * @param  int $count
 * @param  array $array
 * @return array
 */
function take() {
    $take = function($count, $array) {
        if(is_string($array))
            return ($count > 0) ? substr($array, 0, $count) : '';
        return ($count > 0) ? array_slice($array, 0, $count) : [];
    };
    return apply(curry($take), func_get_args());
}

/**
 * Converts an associative array to an array of [key,value] pairs.
 * ```php
 * $array = ['key' => 'value', 'number' => 53, 'foo', 'bar'];
 * toPairs($array); // [['key', 'value'], ['number', 53], [0, 'foo'], [1, 'bar']]
 * ```
 *
 * @signature [a => b] -> [(a,b)]
 * @param  array $array
 * @return array
 */
function toPairs($array) {
    return map(function($key) use($array) {
        return [$key, $array[$key]];
    }, array_keys($array));
}

/**
 * Applies a function to items of the array and concatenates the results.
 * This is also known as `flatMap` in some libraries.
 * ```php
 * $words = chain(split(' '));
 * $words(['Hello World', 'How are you']) // ['Hello', 'World', 'How', 'are', 'you']
 * ```
 *
 * @signature (a -> [b]) -> [a] -> [b]
 * @param  callable $fn
 * @param  array $array
 * @return array
 */
function chain() {
    $chain = function($fn, $array) {
        return reduce('Tarsana\\Functional\\concat', [], map($fn, $array));
    };
    return apply(curry($chain), func_get_args());
}
