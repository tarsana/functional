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

/**
 * Gets the type of the given argument.
 * ```php
 * type(null); // 'Null'
 * type(true); // 'Boolean'
 * type(false); // 'Boolean'
 * type('Hello World'); // 'String'
 * type(1234); // 'Number'
 * type('123'); // 'String'
 * type(function($x) {return $x;}); // 'Function'
 * type(new \stdClass); // 'Object'
 * type(['name' => 'Foo', 'age' => 21]); // 'ArrayObject'
 * type(['Hello', 'World', 123, true]); // 'List'
 * type(['name' => 'Foo', 'Hello', 'Mixed']); // 'Array'
 * type(fopen('php://temp')); // 'Resource'
 * type(Error::of('Ooops !')); // 'Error'
 * // Anything else is 'Unknown'
 * ```
 *
 * @param  mixed $data
 * @return string
 */
function type($data) {
    if (null === $data) return 'Null';
    if (true === $data || false === $data) return 'Boolean';
    if ($data instanceof Error) return 'Error';
    if ($data instanceof Stream) return 'Stream';
    if (is_callable($data)) return 'Function';
    if (is_resource($data)) return 'Resource';
    if (is_string($data)) return 'String';
    if (is_integer($data) || is_float($data)) return 'Number';
    if (is_array($data)) {
        if (all('is_numeric', array_keys($data)))
            return 'List';
        if (all('is_string', array_keys($data)))
            return 'ArrayObject';
        return 'Array';
    }
    if (is_object($data)) return 'Object';
    return 'Unknown';
}
