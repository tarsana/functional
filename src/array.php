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

