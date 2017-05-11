<?php namespace Tarsana\Functional;

/**
 * Functions dealing with functions.
 * @file
 */

/**
 * Returns a curried equivalent of the provided function.
 *
 * ```php
 * $add = F\curry(function($x, $y) {
 *     return $x + $y;
 * });
 *
 * $add(1, 2); //=> 3
 * $addFive = $add(5); // this is a function
 * $addFive(1); //=> 6
 *
 * $data = [1, 2, 3, 4, 5];
 * $slice = F\curry('array_slice');
 * $itemsFrom = $slice($data);
 * $itemsFrom(2); //=> [3, 4, 5]
 * $itemsFrom(1, 2); //=> [2, 3, 4, 5]
 * // Notice that optional arguments are ignored !
 *
 * $polynomial = F\curry(function($a, $b, $c, $x) {
 *     return $a * $x * $x + $b * $x + $c;
 * });
 * $f = $polynomial(0, 2, 1); // 2 * $x + 1
 * $f(5); //=> 11
 * ```
 *
 * @signature (* -> a) -> (* -> a)
 * @param  callable $fn
 * @return callable
 */
function curry($fn) {
    $n = _number_of_args($fn);
    switch($n) {
        case 0: return $fn;
        case 1: return _curry_one($fn);
        case 2: return _curry_two($fn);
        case 3: return _curry_three($fn);
    }
    return _curry_n($fn, $n);
}

/**
 * Argument placeholder to use with curried functions.
 *
 * ```php
 * $reduce = F\curry('array_reduce');
 * $sum = $reduce(F\__(), F\plus());
 * $sum([1, 2, 3, 4], 0); //=> 10
 *
 * $polynomial = F\curry(function($a, $b, $c, $x) {
 *     return $a * $x * $x + $b * $x + $c;
 * });
 *
 * $multiplier = $polynomial(0, F\__(), 0, F\__());
 * $triple = $multiplier(3);
 * $triple(5); //=> 15
 * $multipleOfThree = $multiplier(F\__(), 3);
 * $multipleOfThree(4); //=> 12
 * ```
 *
 * @signature * -> Placeholder
 * @return Tarsana\Functional\Placeholder
 */
function __() {
    return Placeholder::get();
}

/**
 * Apply the provided function to the list of arguments.
 *
 * ```php
 * F\apply('strlen', ['Hello']); //=> 5
 * $replace = F\apply('str_replace');
 * $replace(['l', 'o', 'Hello']); //=> 'Heooo'
 * ```
 *
 * @signature (*... -> a) -> [*] -> a
 * @param  callable $fn
 * @param  array    $args
 * @return mixed
 */
function apply() {
    static $apply = false;
    $apply = $apply ?: curry(_f('_apply'));
    return _apply($apply, func_get_args());
}

/**
 * Performs left-to-right function composition.
 *
 * The leftmost function may have any arity;
 * the remaining functions must be unary.
 * The result of pipe is **not curried**.
 * **Calling pipe() without any argument returns the `identity` function**.
 *
 * ```php
 * $double = function($x) { return 2 * $x; };
 * $addThenDouble = F\pipe(F\plus(), $double);
 * $addThenDouble(2, 3); //=> 10
 * ```
 *
 * @signature (((a, b, ...) -> o), (o -> p), ..., (y -> z)) -> ((a, b, ...) -> z)
 * @param  callable $fns...
 * @return callable
 */
function pipe() {
    $fns = func_get_args();
    if(count($fns) < 1)
        return identity();
    return function () use ($fns) {
        $result = _apply(array_shift($fns), func_get_args());
        foreach ($fns as $fn) {
            $result = $fn($result);
        }
        return $result;
    };
}

/**
 * Performs right-to-left function composition.
 *
 * The rightmost function may have any arity;
 * the remaining functions must be unary.
 * The result of `compose` is **not curried**.
 * **Calling compose() without any argument returns the `identity` function**.
 *
 * ```php
 * $double = function($x) { return 2 * $x; };
 * $addThenDouble = F\compose($double, F\plus());
 * $addThenDouble(2, 3); //=> 10
 * ```
 *
 * @signature (((a, b, ...) -> o), (o -> p), ..., (y -> z)) -> ((a, b, ...) -> z)
 * @param  callable $fns...
 * @return callable
 */
function compose() {
    $fns = array_reverse(func_get_args());
    if(count($fns) < 1)
        return identity();
    return function () use ($fns) {
        $result = _apply(array_shift($fns), func_get_args());
        foreach ($fns as $fn) {
            $result = $fn($result);
        }
        return $result;
    };
}

/**
 * A function that takes one argument and
 * returns exactly the given argument.
 *
 * ```php
 * F\identity('Hello'); //=> 'Hello'
 * F\identity([1, 2, 3]); //=> [1, 2, 3]
 * F\identity(null); //=> null
 * ```
 *
 * @signature * -> *
 * @return mixed
 */
function identity() {
    static $identity = false;
    $identity = $identity ?: curry(function($value) {
        return $value;
    });
    return _apply($identity, func_get_args());
}

/**
 * Returns a function which whenever called will return the specified value.
 *
 * ```php
 * $five = F\give(5);
 * $five(); //=> 5
 * $null = F\give(null);
 * $null(); //=> null
 * ```
 *
 * @signature a -> (* -> a)
 * @param  mixed $value
 * @return callable
 */
function give() {
    static $give = false;
    $give = $give ?: curry(function($value) {
        return function() use($value) {
            return $value;
        };
    });
    return _apply($give, func_get_args());
}

/**
 * Takes many predicates and returns a new predicate that
 * returns `true` only if all predicates are satisfied.
 *
 * If no predicate is given as argument, this function
 * will return an always passing predicate.
 * ```php
 * $betweenOneAndTen = F\all(F\lt(1), F\gt(10));
 * $betweenOneAndTen(5); //=> true
 * $betweenOneAndTen(0); //=> false
 * $alwaysTrue = F\all();
 * $alwaysTrue(1); //=> true
 * $alwaysTrue(null); //=> true
 * ```
 *
 * @signature ((a -> Boolean), ..., (a -> Boolean)) -> (a -> Boolean)
 * @param  callable $predicates...
 * @return callable
 */
function all() {
    $predicates = func_get_args();
    return _curry_one(function($value) use(&$predicates) {
        foreach ($predicates as $predicate) {
            if (! $predicate($value))
                return false;
        }
        return true;
    });
}

/**
 * Takes many predicates and returns a new predicate that
 * returns `true` if any of the predicates is satisfied.
 *
 * If no predicate is given as argument, this function
 * will return an always non-passing predicate.
 * ```php
 * $startsOrEndsWith = function($text) {
 *     return F\any(F\startsWith($text), F\endsWith($text));
 * };
 * $test = $startsOrEndsWith('b');
 * $test('bar'); //=> true
 * $test('bob'); //=> true
 * $test('foo'); //=> false
 * $alwaysFlase = F\any();
 * $alwaysFlase(1); //=> false
 * $alwaysFlase(null); //=> false
 * ```
 *
 * @signature ((a -> Boolean), ..., (a -> Boolean)) -> (a -> Boolean)
 * @param  callable $predicates...
 * @return callable
 */
function any() {
    $predicates = func_get_args();
    return _curry_one(function($value) use(&$predicates) {
        foreach ($predicates as $predicate) {
            if ($predicate($value))
                return true;
        }
        return false;
    });
}

/**
 * Takes a function `f` and returns a function `g` so that if `f` returns
 * `x` for some arguments; `g` will return `! x` for the same arguments.
 *
 * Note that `complement($fn) == pipe($fn, not())`, So the resulting function is not curried !.
 * ```php
 * $isOdd = function($number) {
 *     return 1 == $number % 2;
 * };
 *
 * $isEven = F\complement($isOdd);
 *
 * $isEven(5); //=> false
 * $isEven(8); //=> true
 * ```
 *
 * @signature (* -> ... -> *) -> (* -> ... -> Boolean)
 * @param  callable $fn
 * @return callable
 */
function complement() {
    static $complement = false;
    $complement = $complement ?: curry(function($fn) {
        return function() use($fn) {
            return !_apply($fn, func_get_args());
        };
    });
    return _apply($complement, func_get_args());
}

/**
 * Takes a function telling if the first argument is less then the second, and return a compare function.
 *
 * A compare function returns `-1`, `0`, or `1` if the first argument is considered
 * to be respectively less than, equal to, or greater than the second.
 * ```php
 * $users = [
 *     ['name' => 'foo', 'age' => 21],
 *     ['name' => 'bar', 'age' => 11],
 *     ['name' => 'baz', 'age' => 15]
 * ];
 *
 * usort($users, F\comparator(function($a, $b){
 *     return $a['age'] < $b['age'];
 * }));
 *
 * F\map(F\get('name'), $users); //=> ['bar', 'baz', 'foo']
 * ```
 *
 * @signature (a -> a -> Boolean) -> (a -> a -> Number)
 * @param  callable $fn
 * @return callable
 */
function comparator() {
    static $comparator = false;
    $comparator = $comparator ?: curry(function($fn) {
        return function($a, $b) use($fn) {
            if ($fn($a, $b)) return -1;
            if ($fn($b, $a)) return 1;
            return 0;
        };
    });
    return _apply($comparator, func_get_args());
}
