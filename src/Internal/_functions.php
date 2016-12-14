<?php namespace Tarsana\Functional;
/**
 * This file contains common internal functions
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
 * Adds new given arguments to the list of bound arguments while filling placeholders.
 *
 * @signature Number -> [a] -> [a] -> [a]
 * @param  int   $fnArgsCount
 * @param  array $boundArgs
 * @param  array $givenArgs
 * @return array
 */
function _merge_args($fnArgsCount, $boundArgs, $givenArgs) {
    $addArgument = function($currentBoundArgs, $arg) use($fnArgsCount) {
        $currentBoundArgsCount = count($currentBoundArgs);
        $placeholderPosition = 0;
        while($placeholderPosition < $currentBoundArgsCount && !_is_placeholder($currentBoundArgs[$placeholderPosition]))
            $placeholderPosition ++;
        if ($currentBoundArgsCount < $fnArgsCount || $placeholderPosition == $currentBoundArgsCount) {
            $currentBoundArgs[] = $arg;
        } else { // There is a placeholder and number of bound args >= $fnArgsCount
            $currentBoundArgs[$placeholderPosition] = $arg;
        }
        return $currentBoundArgs;
    };

    return array_reduce($givenArgs, $addArgument, $boundArgs);
}

/**
 * Returns the curried version of a function with some arguments bound to it.
 *
 * @signature (* -> *) -> Number -> [*] -> (* -> *)
 * @param  callable $fn
 * @param  int $argsCount
 * @param  array  $boundArgs
 * @return callable
 */
function _curried_function($fn, $argsCount, $boundArgs = []) {
    return function() use($fn, $argsCount, $boundArgs) {
        $boundArgs = _merge_args($argsCount, $boundArgs, func_get_args());
        $numberOfPlaceholders = count(array_filter($boundArgs, _f('_is_placeholder')));
        $numberOfGivenArgs = count($boundArgs) - $numberOfPlaceholders;
        if ($numberOfGivenArgs >= $argsCount)
            return call_user_func_array($fn, $boundArgs);
        return _curried_function($fn, $argsCount, $boundArgs);
    };
}

/**
 * Non curried version of apply for internal use.
 *
 * @param  callable $fn
 * @param  array    $args
 * @return mixed
 */
function _apply($fn, $args) {
    return call_user_func_array($fn, $args);
}
