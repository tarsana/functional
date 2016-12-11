<?php namespace Tarsana\Functional;

/**
 * This file contains operators as functions.
 * @file
 */

/**
 * Returns `$a && $b`.
 *
 * ```php
 * $isTrue = F\and_(true);
 * $isTrue(false); //=> false
 * $isTrue(true); //=> true
 * ```
 *
 * @signature Boolean -> Boolean -> Boolean
 * @param  bool $a
 * @param  bool $b
 * @return bool
 */
function and_() {
    static $and = false;
    $and = $and ?: curry(function($a, $b){
        return $a && $b;
    });
    return _apply($and, func_get_args());
}

/**
 * Returns `$a || $b`.
 *
 * ```php
 * $isTrue = F\or_(false);
 * $isTrue(false); //=> false
 * $isTrue(true); //=> true
 * ```
 *
 * @signature Boolean -> Boolean -> Boolean
 * @param  bool $a
 * @param  bool $b
 * @return bool
 */
function or_() {
    static $or = false;
    $or = $or ?: curry(function($a, $b){
        return $a || $b;
    });
    return _apply($or, func_get_args());
}

/**
 * Returns `!$x`.
 *
 * ```php
 * F\map(F\not(), [true, false, true]); //=> [false, true, false]
 * ```
 *
 * @signature Boolean -> Boolean
 * @param  bool $x
 * @return bool
 */
function not() {
    static $not = false;
    $not = $not ?: curry(function($x) {
        return !$x;
    });
    return _apply($not, func_get_args());
}

/**
 * Returns `$x == $y`.
 *
 * ```php
 * F\eq('10', 10); //=> true
 * ```
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function eq() {
    $eq = curry(function($a, $b){
        return $a == $b;
    });
    return _apply($eq, func_get_args());
}

/**
 * Returns `$x != $y`.
 *
 * ```php
 * F\notEq('Hi', 'Hello'); //=> true
 * ```
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function notEq() {
    static $notEq = false;
    $notEq = $notEq ?: curry(function($a, $b){
        return $a != $b;
    });
    return _apply($notEq, func_get_args());
}

/**
 * Returns `$x === $y`.
 *
 * ```php
 * F\eqq(10, '10'); //=> false
 * ```
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function eqq() {
    static $eqq = false;
    $eqq = $eqq ?: curry(function($a, $b){
        return $a === $b;
    });
    return _apply($eqq, func_get_args());
}

/**
 * Returns `$x !== $y`.
 *
 * ```php
 * F\notEqq(10, '10'); //=> true
 * ```
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function notEqq() {
    static $notEqq = false;
    $notEqq = $notEqq ?: curry(function($a, $b){
        return $a !== $b;
    });
    return _apply($notEqq, func_get_args());
}

/**
 * Returns `true` if the two elements have the same type and are deeply equivalent.
 *
 * ```php
 * $a = (object) ['a' => 1, 'b' => (object) ['c' => 'Hello'], 'd' => false];
 * $b = (object) ['a' => 1, 'b' => (object) ['c' => 'Hi'], 'd' => false];
 * $c = (object) ['a' => 1, 'b' => ['c' => 'Hello'], 'd' => false];
 * // should have the same type
 * F\equals(5, '5'); //=> false
 * F\equals([1, 2, 3], [1, 2, 3]); //=> true
 * // should have the same order
 * F\equals([1, 3, 2], [1, 2, 3]); //=> false
 * F\equals($a, $b); //=> false
 * F\equals($a, $c); //=> false
 * $b->b->c = 'Hello';
 * F\equals($a, $b); //=> true
 * ```
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function equals() {
    static $equals = false;
    $equals = $equals ?: curry(function($a, $b) {
            $type = type($a);
            if ($type != type($b))
                return false;
            switch ($type) {
                case 'Null':
                case 'Boolean':
                case 'String':
                case 'Number':
                case 'Unknown':
                case 'Function':
                case 'Resource':
                case 'Error':
                case 'Stream':
                    return $a == $b;
                case 'List':
                    $length = length($a);
                    return length($b) != $length ? false :
                           0 == $length ? true :
                           equals(head($a), head($b)) && equals(tail($a), tail($b));
                case 'Array':
                case 'ArrayObject':
                case 'Object':
                    return equals(keys($a), keys($b)) && equals(values($a), values($b));
            }
    });
    return _apply($equals, func_get_args());
}


/**
 * Returns `$a < $b`.
 *
 * ```php
 * F\lt(3, 5); //=> true
 * F\lt(5, 5); //=> false
 * ```
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function lt() {
    static $lt = false;
    $lt = $lt ?: curry(function($a, $b){
        return $a < $b;
    });
    return _apply($lt, func_get_args());
}

/**
 * Returns `$a <= $b`.
 *
 * ```php
 * F\lte(3, 5); //=> true
 * F\lte(5, 5); //=> true
 * ```
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function lte() {
    static $lte = false;
    $lte = $lte ?: curry(function($a, $b){
        return $a <= $b;
    });
    return _apply($lte, func_get_args());
}

/**
 * Returns `$a > $b`.
 *
 * ```php
 * F\gt(5, 3); //=> true
 * F\gt(5, 5); //=> false
 * ```
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function gt() {
    static $gt = false;
    $gt = $gt ?: curry(function($a, $b){
        return $a > $b;
    });
    return _apply($gt, func_get_args());
}

/**
 * Returns `$a >= $b`.
 *
 * ```php
 * F\gte(5, 3); //=> true
 * F\gte(5, 5); //=> true
 * ```
 *
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function gte() {
    static $gte = false;
    $gte = $gte ?: curry(function($a, $b){
        return $a >= $b;
    });
    return _apply($gte, func_get_args());
}
