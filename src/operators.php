<?php namespace Tarsana\Functional;
/**
 * This file contains operators as functions.
 */

/**
 * Returns `$a && $b`.
 *
 * @signature Boolean -> Boolean -> Boolean
 * @param  bool $a
 * @param  bool $b
 * @return bool
 */
function and_() {
    return apply(curry(function($a, $b){
        return $a && $b;
    }), func_get_args());
}

/**
 * Returns `$a || $b`.
 *
 * @signature Boolean -> Boolean -> Boolean
 * @param  bool $a
 * @param  bool $b
 * @return bool
 */
function or_() {
    return apply(curry(function($a, $b){
        return $a || $b;
    }), func_get_args());
}

/**
 * Returns `!$x`.
 *
 * @signature Boolean -> Boolean
 * @param  bool $x
 * @return bool
 */
function not($x) {
    return !$x;
}

/**
 * Returns `$x == $y`.
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function eq() {
    return apply(curry(function($a, $b){
        return $a == $b;
    }), func_get_args());
}

/**
 * Returns `$x != $y`.
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function notEq() {
    return apply(curry(function($a, $b){
        return $a != $b;
    }), func_get_args());
}

/**
 * Returns `$x === $y`.
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function eqq() {
    return apply(curry(function($a, $b){
        return $a === $b;
    }), func_get_args());
}

/**
 * Returns `$x !== $y`.
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function notEqq() {
    return apply(curry(function($a, $b){
        return $a !== $b;
    }), func_get_args());
}

/**
 * Returns `$x < $y`.
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function lt() {
    return apply(curry(function($a, $b){
        return $a < $b;
    }), func_get_args());
}

/**
 * Returns `$x <= $y`.
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function lte() {
    return apply(curry(function($a, $b){
        return $a <= $b;
    }), func_get_args());
}

/**
 * Returns `$x > $y`.
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function gt() {
    return apply(curry(function($a, $b){
        return $a > $b;
    }), func_get_args());
}

/**
 * Returns `$x >= $y`.
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function gte() {
    return apply(curry(function($a, $b){
        return $a >= $b;
    }), func_get_args());
}
