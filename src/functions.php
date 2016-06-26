<?php namespace Tarsana\Functional;
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
    return \Cypress\Curry\curry($fn);
}

/**
 * Apply the provided function to the list of arguments.
 *
 * @signture (*... -> a) -> [*] -> a
 * @param  callable $fn
 * @param  array    $args
 * @return mixed
 */
function apply(callable $fn, $args) {
    $apply = curry(function($fn, $args){
        return call_user_func_array($fn, $args);
    });
    return $apply($fn, $args);
}
