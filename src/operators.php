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
 * @stream
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
 * @stream
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
 * @stream
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
 * @stream
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
 * @stream
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
 * @stream
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
 * @stream
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
 * @stream
 * @signature * -> * -> Boolean
 * @param  mixed $a
 * @param  mixed $b
 * @return bool
 */
function equals() {
    static $equals = false;
    $equals = $equals ?: curry(_f('_equals'));
    return _apply($equals, func_get_args());
}
function _equals($a, $b) {
    $type = type($a);
    if ($type != type($b))
        return false;
    switch ($type) {
        case 'List':
            $length = count($a);
            if (count($b) != $length)
                return false;
            $index = 0;
            while ($index < $length) {
                if (!_equals($a[$index], $b[$index]))
                    return false;
                $index ++;
            }
            return true;
        case 'Array':
        case 'ArrayObject':
        case 'Object':
            $keysA = keys($a);
            $keysB = keys($b);
            $length = count($keysA);
            if (count($keysB) != $length)
                return false;
            $index = 0;
            while ($index < $length) {
                if (!_equals($keysA[$index], $keysB[$index]))
                    return false;
                if (!_equals(get($keysA[$index], $a), get($keysB[$index], $b)))
                    return false;
                $index ++;
            }
            return true;
        default:
            return $a == $b;
    }
}

/**
 * Returns `true` if the results of applying `$fn` to `$a` and `$b` are deeply equal.
 *
 * ```php
 * $headEquals = F\equalBy(F\head());
 * $headEquals([1, 2], [1, 3]); //=> true
 * $headEquals([3, 2], [1, 3]); //=> false
 *
 * $sameAge = F\equalBy(F\get('age'));
 * $foo = ['name' => 'foo', 'age' => 11];
 * $bar = ['name' => 'bar', 'age' => 13];
 * $baz = ['name' => 'baz', 'age' => 11];
 * $sameAge($foo, $bar); //=> false
 * $sameAge($foo, $baz); //=> true
 * ```
 *
 * @stream
 * @signature (a -> b) -> a -> a -> Boolean
 * @return [type] [description]
 */
function equalBy() {
    static $equalBy = false;
    $equalBy = $equalBy ?: curry(function($fn, $a, $b) {
        return _equals($fn($a), $fn($b));
    });
    return _apply($equalBy, func_get_args());
}

/**
 * Returns `$a < $b`.
 *
 * ```php
 * F\lt(3, 5); //=> true
 * F\lt(5, 5); //=> false
 * ```
 *
 * @stream
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
 * @stream
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
 * @stream
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
 * @stream
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

