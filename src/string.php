<?php namespace Tarsana\Functional;
/**
 * This file contains some useful String functions.
 * @file
 */

/**
 * Curried version of `explode`.
 *
 * ```php
 * $words = F\split(' ');
 * $words('Hello World'); //=> ['Hello', 'World']
 * ```
 *
 * @stream
 * @signature String -> String -> [String]
 * @param string $delimiter
 * @param string $string
 * @return array
 */
function split() {
    static $split = false;
    $split = $split ?: curry('explode');
    return _apply($split, func_get_args());
}

/**
 * Curried version of `implode`.
 *
 * ```php
 * $sentence = F\join(' ');
 * $sentence(['Hello', 'World']); //=> 'Hello World'
 * ```
 *
 * @stream
 * @signature String -> [String] -> String
 * @param string $glue
 * @param array $pieces
 * @return string
 */
function join() {
    static $join = false;
    $join = $join ?: curry(function($glue, $pieces){
        return implode($glue, $pieces);
    });
    return _apply($join, func_get_args());
}

/**
 * Curried version of `str_replace`.
 *
 * ```php
 * $string = 'a b c d e f';
 * $noSpace = F\replace(' ', '');
 * $noSpace($string); //=> 'abcdef'
 * F\replace(['a', 'b', ' '], '', $string); //=> 'cdef'
 * F\replace(['a', 'e', ' '], ['x', 'y', ''], $string); //=> 'xbcdyf'
 * ```
 *
 * @stream
 * @signature String|[String] -> String|[String] -> String -> String
 * @param  string $search
 * @param  string $replacement
 * @param  string $string
 * @return string
 */
function replace() {
    static $replace = false;
    $replace = $replace ?: curry('str_replace');
    return _apply($replace, func_get_args());
}

/**
 * Curried version of `preg_replace`.
 *
 * ```php
 * $string = 'A12;b_{F}|d';
 * $alpha = F\regReplace('/[^a-z]+/i', '');
 * $alpha($string); //=> 'AbFd'
 * ```
 *
 * @stream
 * @signature String -> String -> String -> String
 * @param  string $pattern
 * @param  string $replacement
 * @param  string $string
 * @return string
 */
function regReplace() {
    static $regReplace = false;
    $regReplace = $regReplace ?: curry('preg_replace');
    return _apply($regReplace, func_get_args());
}

/**
 * Alias of `strtoupper`.
 *
 * ```php
 * F\upperCase('hello'); //=> 'HELLO'
 * ```
 *
 * @stream
 * @signature String -> String
 * @param  string $string
 * @return string
 */
function upperCase() {
    static $upperCase = false;
    $upperCase = $upperCase ?: curry('strtoupper');
    return _apply($upperCase, func_get_args());
}

/**
 * Alias of `strtolower`.
 *
 * ```php
 * F\lowerCase('HeLLO'); //=> 'hello'
 * ```
 *
 * @stream
 * @signature String -> String
 * @param  string $string
 * @return string
 */
function lowerCase() {
    static $lowerCase = false;
    $lowerCase = $lowerCase ?: curry('strtolower');
    return _apply($lowerCase, func_get_args());
}

/**
 * Gets the camlCase version of a string.
 *
 * ```php
 * F\camelCase('Yes, we can! 123'); //=> 'yesWeCan123'
 * ```
 *
 * @stream
 * @signature String -> String
 * @param  string $string
 * @return string
 */
function camelCase() {
    static $camelCase = false;
    $camelCase = $camelCase ?: curry(function($string) {
        return _apply(pipe(
            regReplace('/[^a-z0-9]+/i', ' '),
            'trim',
            'ucwords',
            replace(' ', ''),
            'lcfirst'
        ), [$string]);
    });
    return _apply($camelCase, func_get_args());
}

/**
 * Gets the snake-case of the string using `$delimiter` as separator.
 *
 * ```php
 * $underscoreCase = F\snakeCase('_');
 * $underscoreCase('IAm-Happy'); //=> 'i_am_happy'
 * ```
 *
 * @stream
 * @signature String -> String -> String
 * @param  string $delimiter
 * @param  string $string
 * @return string
 */
function snakeCase() {
    static $snackCase = false;
    $snackCase = $snackCase ?: curry(function($delimiter, $string) {
        return _apply(pipe(
            regReplace('/([A-Z])/', ' \\1'),
            regReplace('/([0-9]+)/', ' \\1'),
            regReplace('/[^a-z0-9]+/i', ' '),
            'trim',
            'strtolower',
            replace(' ', $delimiter)
        ), [$string]);
    });
    return _apply($snackCase, func_get_args());
}

/**
 * Checks if `$string` starts with `$token`.
 *
 * ```php
 * $http = F\startsWith('http://');
 * $http('http://gitbub.com'); //=> true
 * $http('gitbub.com'); //=> false
 * ```
 *
 * @stream
 * @signature String -> String -> Boolean
 * @param  string $token
 * @param  string $string
 * @return bool
 */
function startsWith() {
    static $startsWith = false;
    $startsWith = $startsWith ?: curry(function($token, $string) {
        return (
            strlen($token) <= strlen($string) &&
            substr($string, 0, strlen($token)) === $token
        );
    });
    return _apply($startsWith, func_get_args());
}

/**
 * Checks if `$string` ends with `$token`.
 *
 * ```php
 * $dotCom = F\endsWith('.com');
 * $dotCom('http://gitbub.com'); //=> true
 * $dotCom('php.net'); //=> false
 * ```
 *
 * @stream
 * @signature String -> String -> Boolean
 * @param  string $token
 * @param  string $string
 * @return bool
 */
function endsWith() {
    static $endsWith = false;
    $endsWith = $endsWith ?: curry(function($token, $string) {
        return (
            strlen($token) <= strlen($string) &&
            substr($string, - strlen($token)) === $token
        );
    });
    return _apply($endsWith, func_get_args());
}

/**
 * Checks if a string matches a regular expression.
 *
 * ```php
 * $numeric = F\test('/^[0-9.]+$/');
 * $numeric('123.43'); //=> true
 * $numeric('12a3.43'); //=> false
 * ```
 *
 * @stream
 * @signature String -> String -> Boolean
 * @param  string $pattern
 * @param  string $string
 * @return bool
 */
function test() {
    static $test = false;
    $test = $test ?: curry(function($pattern, $string) {
        return 1 === preg_match($pattern, $string);
    });
    return _apply($test, func_get_args());
}

/**
 * Performs a global regular expression match
 * and returns array of results.
 *
 * ```php
 * $numbers = F\match('/[0-9.]+/');
 * $numbers('Hello World'); //=> []
 * $numbers('12 is 4 times 3'); //=> ['12', '4', '3']
 * ```
 *
 * @stream
 * @signature String -> String -> [String]
 * @param  string $pattern
 * @param  string $string
 * @return array
 */
function match() {
    static $match = false;
    $match = $match ?: curry(function($pattern, $string) {
        $results = [];
        preg_match_all($pattern, $string, $results);
        return $results[0];
    });
    return _apply($match, func_get_args());
}

/**
 * Curried version of `substr_count` with changed order of parameters,
 *
 * ```php
 * $spaces = F\occurences(' ');
 * $spaces('Hello'); //=> 0
 * $spaces('12 is 4 times 3'); //=> 4
 * ```
 *
 * @stream
 * @signature String -> String -> Number
 * @param  string $token
 * @param  string $text
 * @return int
 */
function occurences() {
    static $occurences = false;
    $occurences = $occurences ?: curry(function($token, $text) {
        return substr_count($text, $token);
    });
    return _apply($occurences, func_get_args());
}

/**
 * Splits a string into chunks without spliting any group surrounded with some specified characters.
 *
 * `$surrounders` is a string where each pair of characters specifies
 * the starting and ending characters of a group that should not be split.
 * ```php
 * $groups = F\chunks('(){}', ',');
 * $groups('1,2,(3,4,5),{6,(7,8)},9'); //=> ['1', '2', '(3,4,5)', '{6,(7,8)}', '9']
 *
 * $names = F\chunks('()""', ' ');
 * $names('Foo "Bar Baz" (Some other name)'); //=> ['Foo', '"Bar Baz"', '(Some other name)']
 * ```
 *
 * @stream
 * @signature String -> String -> String -> [String]
 * @param  string $surrounders
 * @param  string $separator
 * @param  sring $text
 * @return array
 */
function chunks() {
    static $chunks = false;
    $chunks = $chunks ?: curry(function($surrounders, $separator, $text) {
        // Let's assume some values to understand how this function works
        // surrounders = '""{}()'
        // separator = ' '
        // $text = 'foo ("bar baz" alpha) beta'

        $surrounders = map(slices(1), slices(2, $surrounders)); // [['"'. '"'], ['{'. '}'], ['(', ')']]
        $openings = map(get(0), $surrounders); // ['"', '{', '(']
        $closings = map(get(1), $surrounders); // ['"', '}', ')']
        $numOfSurrounders = length($surrounders); // 3
        $indexes = keys($surrounders); // [0, 1, 2]

        $items = split($separator, $text); // ['foo', '("bar', 'baz"', 'alpha)', 'beta']

        // The initial state
        $state = (object) [
            'chunks'    => [], //: the resulting chunks
            'counts'   => array_fill(0, $numOfSurrounders, 0), // [0, 0, 0] : count of openings not closed yet
            'total'    => 0 //: total of not closed openings
        ];
        // We will iterate over $items and update the $state while adding them
        // For each item we need to update counts and chunks

        // Updates count for a single surrender (the surrender at $index)
        // $item : the item we are adding
        // $counts : the previous counts
        $updateCountAt = curry(function($item, $counts, $index) use($openings, $closings) {
            $count = occurences(__(), $item);
            return ($openings[$index] == $closings[$index]) ?
                ($counts[$index] + $count($openings[$index])) % 2 :
                $counts[$index] + $count($openings[$index]) - $count($closings[$index]);
        });
        // Updates counts for all surrenders
        $updateCounts = curry(function($item, $counts) use($indexes, $updateCountAt) {
            return map($updateCountAt($item, $counts), $indexes);
        });
        // Adds an item to the state and returns a new state
        $addItem = function($state, $item) use ($separator, $updateCounts){
            $counts = $updateCounts($item, get('counts', $state));
            $newChunks = (0 == $state->total) // if all openings are closed
                ? append($item, $state->chunks) // then add a new chunk
                // else append the item to the last chunk using the separator as glue
                : append(last($state->chunks) . $separator . $item, init($state->chunks));
            return (object) [
                'chunks' => $newChunks,
                'counts' => $counts,
                'total' => sum($counts)
            ];
        };
        // Returns the chunks of the resulting state after adding all items
        return get('chunks', reduce($addItem, $state, $items));
    });
    return _apply($chunks, func_get_args());
}
