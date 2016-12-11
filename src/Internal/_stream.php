<?php namespace Tarsana\Functional;
/**
 * This file contains Stream internal functions.
 * @file
 */

/**
 * Returns supported types in signatures.
 *
 * @internal
 * @signature List
 * @return array
 */
function _stream_types() {
    return [
        'null',
        'boolean',
        'number',
        'string',
        'resource',
        'function',
        'list', // [1, 2, 'Hi']
        'array', // ['foo' => 'bar', 'baz']
        'object',
        'any'
    ];
}

/**
 * Throws an error.
 *
 * @internal
 * @signature String -> *
 *     'unknown-callable', callable
 *     'invalid-signature', string
 *     'unknown-operation', string
 *     'invalid-args', string, [string], [[string]]
 *     'duplicated-operation', string
 * @param  string $type
 * @return void
 */
function _stream_throw_error($type) {
    $params = tail(func_get_args());
    $msg = 'Stream: unknown error happened';
    switch ($type) {
        case 'unknown-callable':
            $fn = is_string($params[0]) ? $params[0] : toString($params[0]);
            $msg = "Stream: unknown callable '{$fn}'";
        break;
        case 'invalid-signature':
            $msg = "Stream: invalid signature '{$params[0]}' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any";
        break;
        case 'unknown-operation':
            $msg = "Stream: Call to unknown operation '{$params[0]}'";
        break;
        case 'invalid-args':
            $types = join(', ', map('ucfirst', $params[1]));
            $signatures = toString(map(pipe(map('ucfirst'), join(' -> ')), $params[2]));
            $msg = "Stream: Call to operation '{$params[0]}' with arguments '{$types}' does not match any signature of this operation which are {$signatures}";
        break;
        case 'duplicated-operation':
            $msg = "Stream: operation '{$params[0]}' already exists";
        break;
    }
    throw Error::of($msg);
}

/**
 * Creates an operation.
 *
 * @signature String -> [String] | String -> (* -> *) -> Object
 * @signature String -> [String] | String -> Object
 * @param  string $name
 * @param  string|array $signatures
 * @param  callable $callable
 * @return array
 */
function _stream_operation($name, $signatures, $callable = null) {
    $signatures = is_string($signatures) ? [$signatures] : $signatures;
    $callable = $callable ?: $name;
    if (!is_callable($callable))
        _stream_throw_error('unknown-callable', $callable);

    return [
        'name' => $name,
        'signatures' => chain(_stream_make_signatures(), $signatures),
        'callable' => $callable
    ];
}

/**
 * Transforms a signature text to an array of signatures, each one is
 * an array of types (ie [['t1', 't2', ..], ['t1', 't2', ..], ...])
 *
 * @signature String -> [[String]]
 * @param  string $text
 * @return array
 */
function _stream_make_signatures() {
    $makeSignatures = function($text) {
        // $text  = 'Number|List -> Number -> String|Array -> Number'

        $parts = map(pipe(lowerCase(), split('|'), map(pipe('trim', _stream_ensure_type($text)))), split('->', $text));
        // $parts = [['number', 'list'], ['number'], ['string', 'array'], ['number']]

        if (length($parts) < 2)
            _stream_throw_error('invalid-signature', $text);

        return reduce(function($result, $part){
            return chain(function($item) use($result){
                return map(append($item), $result);
            }, $part);
        }, [[]], $parts);
        // 0: $result = [[]]
        // 1: $part = ['number', 'list']  => $result = [['number'], ['list']]
        // 2: $part = ['number']          => $result = [['number', 'number'], ['list', 'number']]
        // 2: $part = ['string', 'array'] => $result = [['number', 'number', 'string'], ['list', 'number', 'string'],
        //                                              ['number', 'number', 'array'], ['list', 'number', 'array']]
        // 3: $part = ['number']          => $result = [['number', 'number', 'string', 'number'],
        //                                              ['list', 'number', 'string', 'number'],
        //                                              ['number', 'number', 'array', 'number'],
        //                                              ['list', 'number', 'array', 'number']]
    };
    return apply(curry($makeSignatures), func_get_args());
}

/**
 * Ensures an element of a signature is a correct type or throws an error if not.
 *
 * @signature String -> String -> String
 * @param  string $signature
 * @param  string $type
 * @return string
 */
function _stream_ensure_type() {
    $ensureType = function($signature, $type) {
        if (! contains($type, _stream_types()))
            _stream_throw_error('invalid-signature', $signature);
        return $type;
    };
    return apply(curry($ensureType), func_get_args());
}


function _stream() {
    $stream = function($operations, $data, $transformations, $type, $throwsExceptions) {
        // return new Stream ??
    };
}
