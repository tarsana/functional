<?php namespace Tarsana\Functional;

/**
 * This file contains generic common functions.
 */

/**
 * Gets the type of the given argument.
 * ```php
 * type(null); // 'Null'
 * type(true); // 'Boolean'
 * type(false); // 'Boolean'
 * type('Hello World'); // 'String'
 * type(1234); // 'Number'
 * type('123'); // 'String'
 * type(function($x) {return $x;}); // 'Function'
 * type(new \stdClass); // 'Object'
 * type(['name' => 'Foo', 'age' => 21]); // 'ArrayObject'
 * type(['Hello', 'World', 123, true]); // 'List'
 * type(['name' => 'Foo', 'Hello', 'Mixed']); // 'Array'
 * type(fopen('php://temp')); // 'Resource'
 * type(Error::of('Ooops !')); // 'Error'
 * // Anything else is 'Unknown'
 * ```
 *
 * @param  mixed $data
 * @return string
 */
function type($data) {
    if (null === $data) return 'Null';
    if (true === $data || false === $data) return 'Boolean';
    if ($data instanceof Error) return 'Error';
    if ($data instanceof Stream) return 'Stream';
    if (is_callable($data)) return 'Function';
    if (is_resource($data)) return 'Resource';
    if (is_string($data)) return 'String';
    if (is_integer($data) || is_float($data)) return 'Number';
    if (is_array($data)) {
        if (all('is_numeric', array_keys($data)))
            return 'List';
        if (all('is_string', array_keys($data)))
            return 'ArrayObject';
        return 'Array';
    }
    if (is_object($data)) return 'Object';
    return 'Unknown';
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
            return is_callable([$something, '__toString']) ? $something->__toString() : '[Object]';
        break;
        default:
            return '['.type($something).']';
    }
}

/**
 * Creates a `Stream` containing the provided data.
 * ```php
 * s('! World Hello')
 *     ->then(split(' '))
 *     ->then('array_reverse')
 *     ->then(join(' '))
 *     ->get(); // 'Hello World !'
 * ```
 *
 * @signature a -> Stream(a)
 * @param  mixed $data
 * @return Stream
 */
function s($data) {
    return Stream::of($data);
}
