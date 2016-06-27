<?php namespace Tarsana\Functional;

use Tarsana\Functional\Exceptions\InvalidArgument;
/**
 * This file contains functions dealing with functions.
 */

/**
 * Returns a curried equivalent of the provided function.
 *
 * @signature (* -> a) -> (* -> a)
 * @param  callable $fn
 * @return callable
 */
function curry(callable $fn) {
    // This condition to be removed when using the next version of Curry
    if(\Cypress\Curry\_number_of_required_params($fn) < 2)
        return $fn;
    return \Cypress\Curry\curry($fn);
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
 * @signture (*... -> a) -> [*] -> a
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
