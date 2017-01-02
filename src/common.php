<?php namespace Tarsana\Functional;

/**
 * Generic common functions.
 * @file
 */

/**
 * Gets the type of the given argument.
 *
 * ```php
 * F\type(null); //=> 'Null'
 * F\type(true); //=> 'Boolean'
 * F\type(false); //=> 'Boolean'
 * F\type('Hello World'); //=> 'String'
 * F\type(1234); //=> 'Number'
 * F\type('123'); //=> 'String'
 * F\type(function($x) {return $x;}); //=> 'Function'
 * F\type(new \stdClass); //=> 'Object'
 * F\type(['name' => 'Foo', 'age' => 21]); //=> 'Array'
 * F\type(['Hello', 'World', 123, true]); //=> 'List'
 * F\type(['name' => 'Foo', 'Hello', 'Mixed']); //=> 'Array'
 * F\type(fopen('php://temp', 'w')); //=> 'Resource'
 * F\type(F\Error::of('Ooops !')); //=> 'Error'
 * F\type(F\Stream::of('Hello')); //=> 'Stream'
 * // Anything else is 'Unknown'
 * ```
 *
 * @signature * -> String
 * @param  mixed $data
 * @return string
 */
function type() {
    static $type = false;
    $type = $type ?: _curry_one(function($data) {
        if ($data instanceof Error) return 'Error';
        if ($data instanceof Stream) return 'Stream';
        if (is_callable($data)) return 'Function';
        switch (gettype($data)) {
            case 'boolean':
                return 'Boolean';
            case 'NULL':
                return 'Null';
            case 'integer':
            case 'double':
                return 'Number';
            case 'string':
                return 'String';
            case 'resource':
                return 'Resource';
            case 'array':
                if (allSatisfies('is_numeric', keys($data)))
                    return 'List';
                return 'Array';
            case 'object':
                return 'Object';
            default:
                return 'Unknown';
        }
    });
    return _apply($type, func_get_args());
}

/**
 * Checks if a variable has a specific type.
 *
 * ```php
 * $isNumber = F\is('Number');
 * $isNumber(5); //=> true
 * $isNumber('5'); //=> false
 * F\is('Any', '5'); //=> true
 * F\is('Any', [1, 2, 3]); //=> true
 * ```
 *
 * @stream
 * @signature String -> * -> Boolean
 * @param  string $type
 * @param  mixed $data
 * @return boolean
 */
function is() {
    static $is = false;
    $is = $is ?: curry(function($type, $data) {
        return 'Any' == $type || type($data) == $type;
    });
    return _apply($is, func_get_args());
}

/**
 * Converts a variable to its string value.
 *
 * ```php
 * F\toString(53); //=> '53'
 * F\toString(true); //=> 'true'
 * F\toString(false); //=> 'false'
 * F\toString(null); //=> 'null'
 * F\toString('Hello World'); //=> '"Hello World"'
 * F\toString([]); //=> '[]'
 * F\toString(new \stdClass); //=> '{}'
 * F\toString(function(){}); //=> '[Function]'
 * F\toString(F\Error::of('Ooops')); //=> '[Error: Ooops]'
 * F\toString(F\Stream::of('Hello')); //=> '[Stream of String]'
 * F\toString(fopen('php://temp', 'r')); //=> '[Resource]'
 * F\toString(['hi', 'hello', 'yo']); //=> '["hi", "hello", "yo"]'
 * F\toString([
 *     'object' => null,
 *     'numbers' => [1, 2, 3],
 *     'message'
 * ]); //=> '{object: null, numbers: [1, 2, 3], 0: "message"}'
 * ```
 *
 * @stream
 * @signature * -> String
 * @param  mixed $something
 * @return string
 */
function toString () {
    static $toString = false;
    $toString = $toString ?: curry(function($something) {
        switch (type($something)) {
            case 'String':
                return "\"{$something}\"";
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
                return '[' . join(', ', map(toString(), $something)) . ']';
            case 'Error':
            case 'Stream':
                return $something->__toString();
            case 'Object':
            case 'Array':
                return '{' . join(', ', map(function($pair){
                    return $pair[0].': '. toString($pair[1]);
                }, toPairs($something))) . '}';
            default:
                return '['.type($something).']';
        }
    });
    return _apply($toString, func_get_args());
}

/**
 * Creates a `Stream` containing the provided data.
 *
 * ```php
 * $s = F\s('! World Hello')
 *     ->split(' ')
 *     ->reverse()
 *     ->join(' ');
 * $s->result(); //=> 'Hello World !'
 * ```
 *
 * @signature a -> Stream(a)
 * @param  mixed $data
 * @return Stream
 */
function s($data) {
    return Stream::of($data);
}
