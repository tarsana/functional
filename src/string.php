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
 * Curried version of `substr_count` with changed order of parameters,
 * ```php
 * $spaces = occurences(' ');
 * $spaces('Hello') // 0
 * $spaces('12 is 4 times 3'); // 4
 * ```
 *
 * @signature String -> String -> Number
 * @param  string $token
 * @param  string $text
 * @return int
 */
function occurences() {
    $occurences = function($token, $text) {
        return substr_count($text, $token);
    };
    return apply(curry($occurences), func_get_args());
}

/**
 * Splits a string into chunks without spliting any group surrounded with some
 * specified characters. `$surrounders` is an array of pairs, each pair specifies
 * the starting and ending characters of a group that should not be splitted.
 * `$surrounders` can also be a string instead of array of pairs.
 * ```php
 * $groups = chunks([['(', ')'], ['{', '}']], ',');
 * $groups('1,2,(3,4,5),{6,(7,8)},9'); // ['1', '2', '(3,4,5)', '{6,(7,8)}', '9']
 *
 * $names = chunks('()""', ' ');
 * $names('Foo "Bar Baz" (Some other name)'); // ['Foo', 'Bar Baz', 'Some other name']
 * ```
 *
 * @signature [(String,Sring)] | String -> String -> String -> [String]
 * @param  array $surrounders
 * @param  string $separator
 * @param  sring $text
 * @return array
 */
function chunks() {
    $chunks = function($surrounders, $separator, $text) {
        if (is_string($surrounders))
            $surrounders = map(slices(1), slices(2, $surrounders));
        return s($text)
            ->then(split($separator))
            ->reduce(function($result, $item) use ($separator){
                $count = occurences(__(), $item);
                $counts = map(function($index) use ($result, $count) {
                    return ($result->openings[$index] == $result->closings[$index]) ?
                        ($result->counts[$index] + $count($result->openings[$index])) % 2 :
                        $result->counts[$index] + $count($result->openings[$index]) - $count($result->closings[$index]);
                }, range(0, length($result->counts) - 1));
                if (0 == $result->total) {
                    return (object) [
                        'items'    => append($item, $result->items),
                        'openings' => $result->openings,
                        'closings' => $result->closings,
                        'counts'   => $counts,
                        'total'    => sum($counts)
                    ];
                }
                return (object) [
                    'items'  => append(last($result->items) . $separator . $item, init($result->items)),
                    'openings' => $result->openings,
                    'closings' => $result->closings,
                    'counts'   => $counts,
                    'total'    => sum($counts)
                ];
            }, (object) [
                'items'    => [],
                'openings' => map(value(0), $surrounders),
                'closings' => map(value(1), $surrounders),
                'counts'   => array_fill(0, length($surrounders), 0),
                'total'    => 0
            ])
            ->then(function($data){
                return $data->items;
            })
            ->get();
    };
    return apply(curry($chunks), func_get_args());
}
