<?php namespace Tarsana\Functional;
/**
 * This file contains common internal functions.
 * Caution: Code written here may seems stupid because it
 * contains a lot of duplications and low level optimisation,
 * but this is needed to make the library as efficient as possible.
 * @file
 */

/**
 * Adds the `Tarsana\Functional` namespace to a function name.
 * This is useful to pass non-curried functions as parameter.
 * ```php
 * F\_f('foo'); //=> 'Tarsana\Functional\foo'
 * ```
 *
 * @signature String -> Sring
 * @param  string $name
 * @return string
 */
function _f($name) {
    $name = "Tarsana\\Functional\\{$name}";
    return $name;
}

/**
 * Gets the number of arguments of a function.
 * ```php
 * F\_number_of_args(function($x, $y){}); //=> 2
 * ```
 *
 * @signature (* -> *) -> Number
 * @param  callable $fn
 * @return int
 */
function _number_of_args($fn) {
    $reflector = is_array($fn) ?
        new \ReflectionMethod($fn[0], $fn[1]) :
        new \ReflectionFunction($fn);
    return $reflector->getNumberOfRequiredParameters();
}

/**
 * Non curried version of apply for internal use.
 *
 * ```php
 * $sum = function() {
 *     return F\sum(func_get_args());
 * };
 * F\_apply($sum, [1, 2, 3, 4, 5]); //=> 15
 * F\_apply($sum, [1, 2, 3, 4, 5, 6]); //=> 21
 * ```
 *
 * @param  callable $fn
 * @param  array    $args
 * @return mixed
 */
function _apply($fn, $args) {
    switch (count($args)) {
        case 0: return $fn();
        case 1: return $fn($args[0]);
        case 2: return $fn($args[0], $args[1]);
        case 3: return $fn($args[0], $args[1], $args[2]);
        case 4: return $fn($args[0], $args[1], $args[2], $args[3]);
        case 5: return $fn($args[0], $args[1], $args[2], $args[3], $args[4]);
    }
    return call_user_func_array($fn, $args);
}

/**
 * Checks if `$a` is an argument placeholder.
 * ```php
 * F\_is_placeholder(F\__()); //=> true
 * F\_is_placeholder('other thing'); //=> false
 * ```
 *
 * @signature * -> Boolean
 * @param  mixed  $a
 * @return boolean
 */
function _is_placeholder($a) {
    return $a instanceof Placeholder;
}

/**
 * Curry an unary function.
 *
 * @ignore
 * @signature (a -> b) -> (a -> b)
 * @param  callable $fn
 * @return callable
 */
function _curry_one($fn) {
    return function() use($fn) {
        $args = func_get_args();
        return (count($args) > 0 && ! _is_placeholder($args[0]))
            ? $fn($args[0])
            : _curry_one($fn);
    };
}

/**
 * Curry an binary function.
 *
 * ```php
 * $add = F\_curry_two(function($x, $y) {
 *     return $x + $y;
 * });
 *
 * $addOne = $add(1, F\__());
 * $addOne(2); //=> 3
 * ```
 *
 * @signature (a,b -> c) -> (a -> b -> c)
 * @param  callable $fn
 * @return callable
 */
function _curry_two($fn) {
    return function() use($fn) {
        $args = func_get_args();
        $n = count($args);
        while ($n > 0 && _is_placeholder($args[$n - 1]))
            $n --;
        if ($n == 0)
            return _curry_two($fn);
        if ($n == 1) {
            $a = &$args[0];
            if (_is_placeholder($a))
                return _curry_two($fn);
            return _curry_one(function($b) use($fn, &$a) {
                return $fn($a, $b);
            });
        }
        $a = &$args[0];
        $b = &$args[1];
        if (_is_placeholder($a) && _is_placeholder($b))
            return _curry_two($fn);
        if (_is_placeholder($a))
            return _curry_one(function($_a) use($fn, &$b) {
                return $fn($_a, $b);
            });
        return $fn($args[0], $args[1]);
    };
}

/**
 * Curry a function with 3 arguments.
 *
 * ```php
 * $add = F\_curry_three(function($x, $y, $z) {
 *     return $x + $y + $z;
 * });
 *
 * $add(1, 2, 3); //=> 6
 * $f = $add(F\__(), 2, F\__());
 * $f(1, 3); //=> 6
 * $g = $add(1, F\__(), 3);
 * $g(2); //=> 6
 * $h = $add(F\__(), F\__(), 3);
 * $h(1, 2); //=> 6
 * $i = $add(F\__(), 2, 3);
 * $i(1); //=> 6
 * ```
 *
 * @signature (a,b,c -> d) -> (a -> b -> c -> d)
 * @param  callable $fn
 * @return callable
 */
function _curry_three($fn) {
    return function() use($fn) {
        $args = func_get_args();
        $n = count($args);
        while ($n > 0 && _is_placeholder($args[$n - 1]))
            $n --;
        if ($n == 0)
            return _curry_three($fn);
        if ($n == 1) {
            $a = &$args[0];
            return _curry_two(function($b, $c) use($fn, &$a) {
                return $fn($a, $b, $c);
            });
        }
        if ($n == 2) {
            $a = &$args[0]; $b = &$args[1];

            if (_is_placeholder($a))
                return _curry_two(function($_a, $c) use($fn, &$b) {
                    return $fn($_a, $b, $c);
                });
            return _curry_one(function($c) use($fn, &$a, &$b) {
                return $fn($a, $b, $c);
            });
        }

        $a = &$args[0]; $b = &$args[1]; $c = &$args[2];

        if (_is_placeholder($a) && _is_placeholder($b))
            return _curry_two(function($_a, $_b) use($fn, &$c) {
                return $fn($_a, $_b, $c);
            });
        if (_is_placeholder($a))
            return _curry_one(function($_a) use($fn, &$b, &$c) {
                return $fn($_a, $b, $c);
            });
        if (_is_placeholder($b))
            return _curry_one(function($_b) use($fn, &$a, &$c) {
                return $fn($a, $_b, $c);
            });

        return $fn($a, $b, $c);
    };
}

/**
 * Curry a function with `$n` arguments.
 *
 * ```php
 * $polynomial = F\_curry_n(function($a, $b, $c, $x) {
 *     return $a * $x * $x + $b * $x + $c;
 * }, 4);
 *
 * $linear = $polynomial(0);
 * $linear(2, 1, 5); //=> 11
 * ```
 *
 * @signature (*... -> *) -> Number -> (* -> ... -> *)
 * @param  callable $fn
 * @param  int $n
 * @param  array $given
 * @return callable
 */
function _curry_n($fn, $n, $given = []) {
    return function() use($fn, $n, $given) {
        $args = func_get_args();
        $merged = _merge_args($given, $args, $n);
        $args = $merged->args;
        switch ($merged->placeholders) {
            case 0: return _apply($fn, $args);
            case 1:
                return _curry_one(function($a) use($fn, &$args) {
                    return _apply($fn, _fill_placeholders($args, [$a]));
                });
            case 2:
                return _curry_two(function($a, $b) use($fn, &$args) {
                    return _apply($fn, _fill_placeholders($args, [$a, $b]));
                });
            case 3:
                return _curry_three(function($a, $b, $c) use($fn, &$args) {
                    return _apply($fn, _fill_placeholders($args, [$a, $b, $c]));
                });
        }
        return _curry_n($fn, $n, $args);
    };
}

/**
 * Merges already given with new arguments, filling placeholders in the process.
 * Returns an object holding the resulting args and the number of placeholders left.
 *
 * ```php
 * $given = [F\__(), 2];
 * $args = [1, 3];
 * $newArgs = F\_merge_args($given, $args, 4);
 * $newArgs; //=> (object) ['args' => [1, 2, 3, F\__()], 'placeholders' => 1]
 * ```
 *
 * @param  array &$given
 * @param  array &$args
 * @param  int $n
 * @return object
 */
function _merge_args(&$given, &$args, $n) {
    $merged = (object) [
        'args' => [],
        'placeholders' => 0
    ];
    $givenIndex = 0; $argsIndex = 0; $mergedIndex = 0;
    $givenCount = count($given); $argsCount = count($args);
    while ($mergedIndex < $n && ($givenIndex < $givenCount || $argsIndex < $argsCount)) {
        if ($givenIndex < $givenCount && !_is_placeholder($given[$givenIndex])) {
            $merged->args[$mergedIndex] = $given[$givenIndex];
        } else if ($argsIndex < $argsCount) {
            $merged->args[$mergedIndex] = $args[$argsIndex];
            $argsIndex ++;
        } else {
            $merged->args[$mergedIndex] = $given[$givenIndex];
        }

        if (_is_placeholder($merged->args[$mergedIndex]))
            $merged->placeholders ++;

        $givenIndex ++;
        $mergedIndex ++;
    }
    while ($mergedIndex < $n) {
        $merged->args[$mergedIndex] = Placeholder::get();
        $mergedIndex ++;
        $merged->placeholders ++;
    }
    return $merged;
}

function _fill_placeholders($args, $fillers) {
    $argsIndex = 0; $fillersIndex = 0;
    $argsCount = count($args);
    $fillersCount = count($fillers);
    while ($fillersIndex < $fillersCount) {
        while (!_is_placeholder($args[$argsIndex]))
            $argsIndex ++;
        $args[$argsIndex] = $fillers[$fillersIndex];
        $fillersIndex ++;
    }
    return $args;
}
