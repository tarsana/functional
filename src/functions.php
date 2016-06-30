<?php namespace Tarsana\Functional;
/**
 * This file contains functions dealing with functions.
 */

use Tarsana\Functional\Exceptions\InvalidArgument;

/**
 * Returns a curried equivalent of the provided function.
 * ```php
 * $add = curry(function($x, $y){
 *     return $x + $y;
 * });
 * $addFive = $add(5); // a function
 * $addFive(5); // 10
 * $add(5, 5) // 10
 * ```
 *
 * @signature (* -> a) -> (* -> a)
 * @param  callable $fn
 * @return callable
 */
function curry(callable $fn) {
    return \Cypress\Curry\curry($fn);
}

/**
 * Argument placeholder to use with curried functions.
 * ```php
 * $minus = curry(function ($x, $y) { return $x - $y; });
 * $decrement = $minus(__(), 1);
 * $decrement(10) // 9
 *
 * $reduce = curry('array_reduce');
 * $sum = $reduce(__(), 'Tarsana\Functional\plus');
 * $sum([1, 2, 3, 4], 0) // 10
 * ```
 *
 * @signature * -> Placeholder
 * @return \Cypress\Curry\Placeholder
 */
function __() {
    return \Cypress\Curry\__();
}

/**
 * Non curried version of apply for internal use.
 *
 * @internal
 * @param  callable $fn
 * @param  array    $args
 * @return mixed
 */
function _apply($fn, $args) {
    return call_user_func_array($fn, $args);
}

/**
 * Apply the provided function to the list of arguments.
 * ```php
 * apply('strlen', ['Hello']) // 5
 * $replace = apply('str_replace');
 * $replace(['l', 'o', 'Hello']) // 'Heooo'
 * ```
 *
 * @signature (*... -> a) -> [*] -> a
 * @param  callable $fn
 * @param  array    $args
 * @return mixed
 */
function apply() {
    return _apply(curry('Tarsana\Functional\_apply'), func_get_args());
}

/**
 * Performs left-to-right function composition.
 * The leftmost function may have any arity;
 * the remaining functions must be unary.
 * The result of pipe is curried.
 * **Calling pipe() without any argument throws Tarsana\Functional\Exceptions\InvalidArgument**
 * ```php
 * function add($x, $y) { return $x + $y; }
 * $double = function($x) { return 2 * $x; };
 * $addThenDouble = pipe('add', $double);
 * $addThenDouble(2, 3) // 10
 * ```
 *
 * @signature (((a, b, ...) -> o), (o -> p), ..., (y -> z)) -> ((a, b, ...) -> z)
 * @param  callable ...$fns
 * @return callable
 */
function pipe() {
    $fns = func_get_args();
    if(count($fns) < 1)
        throw new InvalidArgument("pipe() requires at least one argument");
    return curry(function () use ($fns) {
        $result = _apply(array_shift($fns), func_get_args());
        foreach ($fns as $fn) {
            $result = $fn($result);
        }
        return $result;
    });
}

/**
 * A function that takes one argument and
 * returns exactly the given argument.
 * ```php
 * identity('Hello') // 'Hello'
 * identity([1, 2, 3]) // [1, 2, 3]
 * identity(null) // null
 * ```
 *
 * @signature * -> *
 * @return mixed
 */
function identity($value) {
    return $value;
}

