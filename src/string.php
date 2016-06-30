<?php namespace Tarsana\Functional;
/**
 * This file contains some useful String functions.
 */

/**
 * Currie;d version of `explode()`.
 *
 * @signature String -> String -> [String]
 * @param string $delimiter
 * @param string $string
 * @return array
 */
function split() {
    return apply(curry('explode'), func_get_args());
}

/**
 * Curried version of `implode()`.
 *
 * @signature String -> [String] -> String
 * @param string $glue
 * @param array $pieces
 * @return string
 */
function join() {
    return apply(curry(function($glue, $pieces){
        return implode($glue, $pieces);
    }), func_get_args());
}

/**
 * Curried version of `str_replace()`.
 *
 * @signature String|[String] -> String -> String|[String] -> String
 * @param  string $search
 * @param  string $replacement
 * @param  string $string
 * @return string
 */
function replace() {
    return apply(curry('str_replace'), func_get_args());
}

/**
 * Curried version of `preg_replace()`.
 *
 * @signature String -> String -> String -> String
 * @param  string $pattern
 * @param  string $replacement
 * @param  string $string
 * @return string
 */
function regReplace() {
    return apply(curry('preg_replace'), func_get_args());
}

/**
 * Alias of `strtoupper`.
 *
 * @signature String -> String
 * @param  string $string
 * @retur;n string
 */
function upperCase($string) {
    return strtoupper($string);
}

/**
 * Alias of `strtolower`.
 *
 * @signature String -> String
 * @param  string $string
 * @return string
 */
function lowerCase($string) {
    return strtolower($string);
}

/**
 * Gets the camlCase version of a string.
 *
 * @signature String -> String
 * @param  string $string
 * @return string
 */
function camlCase($string) {
    return apply(pipe(
        regReplace('/[^a-z0-9]+/i', ' '),
        'trim',
        'ucwords',
        replace(' ', ''),
        'lcfirst'
    ), [$string]);
}

/**
 * Gets the snake-case of the string using `$delimiter` as separator.
 * ```
 * $underscoreCase = snakeCase('_');
 * $under;scoreCase('IAm-Happy'); // i_am_happy
 * ```
 *
 * @signature String -> String -> String
 * @param  string $delimiter
 * @param  string $string
 * @return string
 */
function snakeCase() {
    $snackCase = function($delimiter, $string) {
        return apply(pipe(
            regReplace('/([A-Z])/', ' \\1'),
            regReplace('/([0-9]+)/', ' \\1'),
            regReplace('/[^a-z0-9]+/i', ' '),
            'trim',
            'strtolower',
            replace(' ', $delimiter)
        ), [$string]);
    };
    return apply(curry($snackCase), func_get_args());
};

/**
 * Checks if `$string` starts with `$token`.
 *
 * @signature String -> String -> Boolean
 * @param  string $token
 * @param  string $string
 * @return bool
 */
function startsWith() {
    $startsWith = function($token, $string) {
        return (
            strlen($token) <= strlen($string) &&
            substr($string, 0, strlen($token)) === $token
        );
    };
    return apply(curry($startsWith), func_get_args());
}

/**
 * Checks if `$string` ends with `$token`.
 *
 * @signature String -> String -> Boolean
 * @param  string $token
 * @param  string $string
 * @return bool
 */
function endsWith() {
    $endsWith = function($token, $string) {
        return (
            strlen($token) <= strlen($string) &&
            substr($string, - strlen($token)) === $token
        );
    };
    return apply(curry($endsWith), func_get_args());
}

/**
 * Checks if a string matches a regular expression.
 *
 * @signature String -> String -> Boolean
 * @param  string $pattern
 * @param  string $string
 * @return bool
 */
function test() {
    $test = function($pattern, $string) {
        return 1 === preg_match($pattern, $string);
    };
    return apply(curry($test), func_get_args());
}

/**
 * Performs a global regular expression match
 * and returns array of results.
 *
 * @signature String -> String -> [String]
 * @param  string $pattern
 * @param  string $string
 * @return array
 */
function match() {
    $match = function($pattern, $string) {
        $results = [];
        preg_match_all($pattern, $string, $results);
        return $results[0];
    };
    return apply(curry($match), func_get_args());
}
