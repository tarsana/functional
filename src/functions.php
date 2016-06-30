<?php namespace Tarsana\Functional;
/**
 * This file contains functions dealing with functions.
 */

use Tarsana\Functional\Exceptions\InvalidArgument;

/**
 * Returns a curried equivalent of the provided function.
 *
 * @signature (* -> a) -> (* -> a)
 * @param  callable $fn
 * @return callable
 */
function curry(callable $fn) {
    return \Cypress\Curry\curry($fn);
}

/**
 * Argument placeholder.
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
 *
 * @signature * -> *
 * @return callable
 */
function identity($value) {
    return $value;
}

