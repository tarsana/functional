<?php namespace Tarsana\Functional;
/**
 * This file contains some useful String functions.
 * @file
 */

/**
 * Some functions are not written in a clean way for efficiency;
 * I hope code is not that much durty
 * @ignore
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
        return lcfirst(str_replace(' ', '', ucwords(trim(preg_replace('/[^a-z0-9]+/i', ' ', $string)))));
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
        return str_replace(' ', $delimiter, trim(strtolower(
            preg_replace('/[^a-z0-9]+/i', ' ',
            preg_replace('/([0-9]+)/', ' \\1',
            preg_replace('/([A-Z])/', ' \\1', $string))))));
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
 *
 * **Note that this function assumes that the given `$text` is well formatted**
 *
 * ```php
 * $names = F\chunks('()""', ' ');
 * $names('Foo "Bar Baz" (Some other name)'); //=> ['Foo', '"Bar Baz"', '(Some other name)']
 *
 * $groups = F\chunks('(){}', '->');
 * $groups('1->2->(3->4->5)->{6->(7->8)}->9'); //=> ['1', '2', '(3->4->5)', '{6->(7->8)}', '9']
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
    // This is by far the most complicated string function
    $chunks = $chunks ?: curry(function($surrounders, $separator, $text) {
        // Let's assume some values to understand how this function works
        // surrounders = '""{}()'
        // separator = ' '
        // $text = 'foo ("bar baz" alpha) beta'
        $counters = [
            'values'   => [], // each item of this array refers to the number
                              // of closings needed for an opening
            'openings' => [], // an associative array where the key is an opening
                              // and the value is the index of corresponding cell
                              // in the 'values' field
            'closings' => [], // associative array for closings like the previous one
            'total'    => 0   // the total number of needed closings
        ];
        foreach (str_split($surrounders) as $key => $char) {
            $counters['values'][$key / 2] = 0;
            if ($key % 2 == 0)
                $counters['openings'][$char] = $key / 2;
            else
                $counters['closings'][$char] = $key / 2;
        }
        // $counters = [
        //   'values'   => [0, 0, 0],
        //   'openings' => ['"' => 0, '{' => 1, '(' => 2],
        //   'openings' => ['"' => 0, '}' => 1, ')' => 2],
        //   'total'    => 0
        // ]
        $result = [];
        $length = strlen($text);
        $separatorLength = strlen($separator);
        $characters = str_split($text);
        $index = 0;
        $buffer = '';
        while ($index < $length) {
            if (substr($text, $index, $separatorLength) == $separator && $counters['total'] == 0) {
                $result[] = $buffer;
                $buffer = '';
                $index += $separatorLength;
            } else {
                $c = $characters[$index];
                $isOpening = array_key_exists($c, $counters['openings']);
                $isClosing = array_key_exists($c, $counters['closings']);
                if ($isOpening && $isClosing) { // when $c == '"' for example
                    $value = $counters['values'][$counters['openings'][$c]];
                    if ($value == 0) {
                        $counters['values'][$counters['openings'][$c]] = 1;
                        $counters['total'] ++;
                    } else {
                        $counters['values'][$counters['openings'][$c]] = 0;
                        $counters['total'] --;
                    }
                } else {
                    if ($isOpening) {
                        $counters['values'][$counters['openings'][$c]] ++;
                        $counters['total'] ++;
                    }
                    if ($isClosing) {
                        $counters['values'][$counters['closings'][$c]] --;
                        $counters['total'] --;
                    }
                }
                $buffer .= $c;
                $index ++;
            }
        }
        if ($buffer != '')
            $result[] = $buffer;

        return $result;
    });
    return _apply($chunks, func_get_args());
}
