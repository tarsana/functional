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
 * @signature [Number] -> Number
 * @param  array $numbers
 * @return int|float
 */
function sum() {
    static $sum = false;
    $sum = $sum ?: curry(function($numbers){
        return reduce(plus(), 0, $numbers);
    });
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
 * @signature [Number] -> Number
 * @param  array $numbers
 * @return int|float
 */
function product() {
    static $product = false;
    $product = $product ?: curry(function($numbers){
        return reduce(multiply(), 1, $numbers);
    });
    return _apply($product, func_get_args());
}
