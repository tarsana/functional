<?php namespace Tarsana\Functional;
/**
 * This file contains some useful String functions.
 */

/**
 * Curried version of `explode()`.
 * ```php
 * $words = split(' ');
 * $words('Hello World'); // ['Hello', 'World']
 * ```
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
 * ```php
 * $sentence = join(' ');
 * $sentence(['Hello', 'World']); // 'Hello World'
 * ```
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
 * ```php
 * $string = 'a b c d e f';
 * $noSpace = replace(' ', '');
 * $noSpace($string); // 'abcdef'
 * replace(['a', 'b', ' '], '', $string) // 'cdef'
 * replace(['a', 'e', ' '], ['x', 'y', ''], $string); // 'xbcdyf'
 * ```
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
 * ```php
 * $string = 'A12;b_{F}|d';
 * $aplha = regReplace('/[^a-z]+/i', '');
 * $alpha($string); // 'AbFd'
 * ```
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
 * ```php
 * upperCase('hello') // 'HELLO'
 * ```
 *
 * @signature String -> String
 * @param  string $string
 * @return string
 */
function upperCase($string) {
    return strtoupper($string);
}

/**
 * Alias of `strtolower`.
 * ```php
 * lowerCase('HELLO') // 'hello'
 * ```
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
 * ```php
 * camelCase('Yes, we can! 123') // 'yesWeCan123'
 * ```
 *
 * @signature String -> String
 * @param  string $string
 * @return string
 */
function camelCase($string) {
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
 * $underscoreCase('IAm-Happy'); // i_am_happy
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
 * ```php
 * $http = startsWith('http://');
 * $http('http://gitbub.com'); // true
 * $http('gitbub.com'); // false
 * ```
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
 * ```php
 * $dotCom = endsWith('.com');
 * $dotCom('http://gitbub.com'); // true
 * $dotCom('php.net'); // false
 * ```
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
 * ```php
 * $numeric = test('/^[0-9.]+$/');
 * $numeric('123.43'); // true
 * $numeric('12a3.43'); // false
 * ```
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
 * ```php
 * $numbers = match('/[0-9.]+/');
 * $numbers('Hello World'); // []
 * $numbers('12 is 4 times 3'); // ['12', '4', '3']
 * ```
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

/**
 * Converts a variable to its string value.
 * ```php
 * toString(53)); // '53'
 * toString(true)); // 'true'
 * toString(false)); // 'false'
 * toString(null)); // 'null'
 * toString('Hello World')); // 'Hello World'
 * toString([])); // '[]'
 * toString(new \stdClass)); // '[Object]'
 * toString(function(){})); // '[Function]'
 * toString(Error::of('Ooops'))); // '[Error: Ooops]'
 * toString(fopen('php://temp', 'r'))); // '[Resource]'
 * toString(['hi', 'hello', 'yo'])); // '[hi, hello, yo]'
 * toString([
 *     'object' => Stream::of(null),
 *     'numbers' => [1, 2, 3],
 *     'message'
 * ]); // '[object => Stream(Null), numbers => [1, 2, 3], 0 => message]'
 * ```
 *
 * @signature * -> String
 * @param  mixed $something
 * @return string
 */
function toString ($something) {
    switch (type($something)) {
        case 'String':
            return $something;
        break;
        case 'Boolean':
            return $something ? 'true' : 'false';
        break;
        case 'Null':
            return 'null';
        break;
        case 'Number':
            return (string) $something;
        break;
        case 'List':
            return '[' . join(', ', map('Tarsana\\Functional\\toString', $something)) . ']';
        break;
        case 'ArrayObject':
        case 'Array':
            return '[' . join(', ', map(function($pair){
                return $pair[0].' => '. toString($pair[1]);
            }, toPairs($something))) . ']';
        break;
        case 'Error':
        case 'Stream':
        case 'Object':
            return method_exists($something, '__toString') ? $something->__toString() : '[Object]';
        break;
        default:
            return '['.type($something).']';
    }
}
