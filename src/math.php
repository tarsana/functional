<?php namespace Tarsana\Functional;
/**
 * Basic Math functions.
 * @file
 */

/**
 * Computes `$x + $y`.
 *
 * ```php
 * $plusTwo = F\plus(2);
 * $plusTwo(5); //=> 7
 * ```
 *
 * @stream
 * @signature Number -> Number -> Number
 * @param  int|float $x
 * @param  int|float $y
 * @return int|float
 */
function plus() {
    static $plus = false;
    $plus = $plus ?: curry(function($x, $y){
        return $x + $y;
    });
    return _apply($plus, func_get_args());
}

/**
 * Computues `$x - $y`.
 *
 * ```php
 * F\minus(7, 2); //=> 5
 * ```
 *
 * @stream
 * @signature Number -> Number -> Number
 * @param  int|float $x
 * @param  int|float $y
 * @return int|float
 */
function minus() {
    static $minus = false;
    $minus = $minus ?: curry(function($x, $y){
        return $x - $y;
    });
    return _apply($minus, func_get_args());
}

/**
 * Computes `- $x`.
 *
 * ```php
 * F\negate(5); //=> -5
 * F\negate(-7); //=> 7
 * ```
 *
 * @stream
 * @signature Number -> Number
 * @param  int|float $x
 * @return int|float
 */
function negate() {
    static $negate = false;
    $negate = $negate ?: curry(function($x){
        return -$x;
    });
    return _apply($negate, func_get_args());
}

/**
 * Computes `$x * $y`.
 *
 * ```php
 * $double = F\multiply(2);
 * $double(5); //=> 10
 * ```
 *
 * @stream
 * @signature Number -> Number -> Number
 * @param  int|float $x
 * @param  int|float $y
 * @return int|float
 */
function multiply() {
    static $multiply = false;
    $multiply = $multiply ?: curry(function($x, $y){
        return $y * $x;
    });
    return _apply($multiply, func_get_args());
}

/**
 * Computes `$x / $y`.
 *
 * ```php
 * F\divide(10, 2); //=> 5
 * ```
 *
 * @stream
 * @signature Number -> Number -> Number
 * @param  int|float $x
 * @param  int|float $y
 * @return int|float
 */
function divide() {
    static $divide = false;
    $divide = $divide ?: curry(function($x, $y){
        return $x / $y;
    });
    return _apply($divide, func_get_args());
}

/**
 * Computes `$x % $y`.
 *
 * ```php
 * F\modulo(10, 3); //=> 1
 * ```
 *
 * @stream
 * @signature Number -> Number -> Number
 * @param  int|float $x
 * @param  int|float $y
 * @return int|float
 */
function modulo() {
    static $modulo = false;
    $modulo = $modulo ?: curry(function($x, $y){
        return $x % $y;
    });
    return _apply($modulo, func_get_args());
}

/**
 * Computes the sum of an array of numbers.
 *
 * ```php
 * F\sum([1, 2, 3, 4]); //=> 10
 * F\sum([]); //=> 0
 * ```
 *
 * @stream
 * @signature [Number] -> Number
 * @param  array $numbers
 * @return int|float
 */
function sum() {
    static $sum = false;
    $sum = $sum ?: curry('array_sum');
    return _apply($sum, func_get_args());
}

/**
 * Computes the product of an array of numbers.
 *
 * ```php
 * F\product([1, 2, 3, 4]); //=> 24
 * F\product([]); //=> 1
 * ```
 *
 * @stream
 * @signature [Number] -> Number
 * @param  array $numbers
 * @return int|float
 */
function product() {
    static $product = false;
    $product = $product ?: curry('array_product');
    return _apply($product, func_get_args());
}

/**
 * Computes the minimum of two numbers.
 *
 * ```php
 * F\min(1, 3); //=> 1
 * F\min(1, -3); //=> -3
 * ```
 *
 * @stream
 * @signature Number -> Number -> Number
 * @param  number $a
 * @param  number $b
 * @return number
 */
function min() {
    static $min = false;
    $min = $min ?: curry(function($a, $b){
        return $a < $b ? $a : $b;
    });
    return _apply($min, func_get_args());
}

/**
 * Computes the minimum of two elements using a function.
 *
 * ```php
 * F\minBy(F\length(), 'Hello', 'Hi'); //=> 'Hi'
 * F\minBy('abs', 1, -3); //=> 1
 * ```
 *
 * @stream
 * @signature (a -> Number) -> a -> a -> a
 * @param  callable $fn
 * @param  mixed $a
 * @param  mixed $b
 * @return mixed
 */
function minBy() {
    static $minBy = false;
    $minBy = $minBy ?: curry(function($fn, $a, $b){
        return $fn($a) < $fn($b) ? $a : $b;
    });
    return _apply($minBy, func_get_args());
}

/**
 * Computes the maximum of two numbers.
 *
 * ```php
 * F\max(1, 3); //=> 3
 * F\max(1, -3); //=> 1
 * ```
 *
 * @stream
 * @signature Number -> Number -> Number
 * @param  number $a
 * @param  number $b
 * @return number
 */
function max() {
    static $max = false;
    $max = $max ?: curry(function($a, $b){
        return $a > $b ? $a : $b;
    });
    return _apply($max, func_get_args());
}

/**
 * Computes the maximum of two elements using a function.
 *
 * ```php
 * F\maxBy(F\length(), 'Hello', 'Hi'); //=> 'Hello'
 * F\maxBy('abs', 1, -3); //=> -3
 * ```
 *
 * @stream
 * @signature (a -> Number) -> a -> a -> a
 * @param  callable $fn
 * @param  mixed $a
 * @param  mixed $b
 * @return mixed
 */
function maxBy() {
    static $maxBy = false;
    $maxBy = $maxBy ?: curry(function($fn, $a, $b){
        return $fn($a) > $fn($b) ? $a : $b;
    });
    return _apply($maxBy, func_get_args());
}
