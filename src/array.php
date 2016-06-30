<?php namespace Tarsana\Functional;
/**
 * This file contains some useful functions to handle arrays.
 */

/**
 * Gets the value of a key.
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
 *
 * @signature (a -> b) -> [a] -> [b]
 * @param  callable $fn
 * @param  array $array
 * @return array
 */
function map() {
    return apply(curry('array_map'), func_get_args());
}

/**
 * Curried version of `array_filter` with modified order of
 * arguments. The callback is the first argument then the array.
 *
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

