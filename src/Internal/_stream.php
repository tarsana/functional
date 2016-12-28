<?php namespace Tarsana\Functional;
/**
 * This file contains Stream internal functions.
 * @file
 */

/**
 * Operation :: {
 *     name: String,
 *     signatures: [[String]],
 *     fn: Function
 * }
 *
 * @type
 */

/**
 * Transformation :: {
 *     operations: [Operation],
 *     args: [Any]
 * }
 *
 * @type
 */

/**
 * Stream :: {
 *     operations: {name1: Operation1, ...},
 *     data: Any,
 *     transformations: [Transformation],
 *     type: String,
 *     result: Any,
 *     resolved: Boolean (The result is computed)
 * }
 *
 * @type
 */

/**
 * Returns supported types in signatures.
 *
 * @signature List
 * @return array
 */
function _stream_types() {
    return [
        'Null',
        'Boolean',
        'Number',
        'String',
        'Resource',
        'Function',
        'List', // [1, 2, 'Hi']
        'Array', // ['foo' => 'bar', 'baz']
        'Object',
        'Any'
    ];
}

/**
 * Throws an error.
 *
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
            $msg = "Stream: call to unknown operation '{$params[0]}'";
        break;
        case 'duplicated-operation':
            $msg = "Stream: operation '{$params[0]}' already exists";
        break;
        case 'ambiguous-signatures':
            $msg = "Stream: signatures of the operation '{$params[0]}' are duplicated or ambiguous";
        break;
        case 'wrong-operation-args':
            $args = join(', ', $params[0]);
            $msg = "Stream: wrong arguments ({$args}) given to operation '{$params[1]}'";
        break;
        case 'wrong-transformation-args':
            $args = join(', ', $params[1]);
            $types = join(' or ', map(pipe(join(', '), prepend('('), append(')')), $params[2]));

            $msg = "Stream: operation '{$params[0]}' could not be called with arguments types ({$args}); expected types are {$types}";
        break;
    }
    throw Error::of($msg);
}

/**
 * Creates an operation.
 *
 * ```php
 * // Using function name
 * $length = F\_stream_operation('length', 'List|Array -> Number', 'count');
 * $length; //=> ['name' => 'length', 'signatures' => [['List', 'Number'], ['Array', 'Number']], 'fn' => 'count']
 * // Using closure
 * $increment = function($x) {
 *     return 1 + $x;
 * };
 * $operation = F\_stream_operation('increment', 'Number -> Number', $increment);
 * $operation; //=> ['name' => 'increment', 'signatures' => [['Number', 'Number']], 'fn' => $increment]
 * // Without callable
 * F\_stream_operation('count', 'List -> Number'); //=> ['name' => 'count', 'signatures' => [['List', 'Number']], 'fn' => 'count']
 * // Invalid signature
 * F\_stream_operation('count', 'Number'); // throws "Stream: invalid signature 'Number' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any"
 * // Invalid callable
 * F\_stream_operation('foo', 'List -> Number'); // throws "Stream: unknown callable 'foo'"
 * ```
 *
 * @signature String -> String -> Maybe(Function) -> Operation
 * @param  string $name
 * @param  string $signature
 * @param  callable $fn
 * @return array
 */
function _stream_operation($name, $signature, $callable = null) {
    $callable = $callable ?: $name;
    if (!is_callable($callable))
        _stream_throw_error('unknown-callable', $callable);

    return [
        'name' => $name,
        'signatures' => _stream_make_signatures($signature),
        'fn' => $callable
    ];
}

/**
 * Transforms a signature text to an array of signatures.
 *
 * ```php
 * F\_stream_make_signatures('Number|List -> Number -> String|Array -> Number'); //=> [
 *     ['Number', 'Number', 'String', 'Number'],
 *     ['List', 'Number', 'String', 'Number'],
 *     ['Number', 'Number', 'Array', 'Number'],
 *     ['List', 'Number', 'Array', 'Number']
 * ]
 * F\_stream_make_signatures('List'); // throws "Stream: invalid signature 'List' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any"
 * F\_stream_make_signatures('List -> Foo'); // throws "Stream: invalid signature 'List -> Foo' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any"
 * ```
 *
 * @signature String -> [[String]]
 * @param  string $text
 * @return array
 */
function _stream_make_signatures($text) {
    // Assuming $text  = 'Number|List -> Number -> String|Array -> Number'
    $parts = map(pipe(split('|'), map(pipe('trim', _stream_ensure_type($text)))), split('->', $text));
    // $parts = [['Number', 'List'], ['Number'], ['String', 'Array'], ['Number']]
    if (length($parts) < 2)
        _stream_throw_error('invalid-signature', $text);

    return reduce(function($result, $part){
        return chain(function($item) use($result){
            return map(append($item), $result);
        }, $part);
    }, [[]], $parts);
    // 0: $result = [[]]
    // 1: $part = ['Number', 'List']  => $result = [['Number'], ['List']]
    // 2: $part = ['Number']          => $result = [['Number', 'Number'], ['List', 'Number']]
    // 2: $part = ['String', 'Array'] => $result = [['Number', 'Number', 'String'], ['List', 'Number', 'String'],
    //                                              ['Number', 'Number', 'Array'], ['List', 'Number', 'Array']]
    // 3: $part = ['Number']          => $result = [['Number', 'Number', 'String', 'Number'],
    //                                              ['List', 'Number', 'String', 'Number'],
    //                                              ['Number', 'Number', 'Array', 'Number'],
    //                                              ['List', 'Number', 'Array', 'Number']]
}

/**
 * Ensures an element of a signature is a correct type or throws an error if not.
 *
 * ```php
 * F\_stream_ensure_type('List -> Bar', 'List'); //=> 'List'
 * F\_stream_ensure_type('List -> Bar', 'Bar'); // throws "Stream: invalid signature 'List -> Bar' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object, Any"
 * ```
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

/**
 * Makes a Stream with the given args.
 *
 * @signature [Operation] -> Any -> [Transformation] -> String -> Any -> Boolean -> Stream
 * @param  array $operations
 * @param  mixed $data
 * @param  array $transformations
 * @param  string $type
 * @param  mixed $result
 * @param  bool $resolved
 * @return array
 */
function _stream_make($operations, $data, $transformations, $type, $result, $resolved) {
    return [
        'data' => $data,
        'type' => $type,
        'result' => $result,
        'resolved' => $resolved,
        'operations' => $operations,
        'transformations' => $transformations
    ];
}

/**
 * Creates a Stream with operations and initial data.
 *
 * ```php
 * $map = F\map();
 * $operations = [
 *     F\_stream_operation('length', 'List|Array -> Number', 'count'),
 *     F\_stream_operation('length', 'String -> Number', 'strlen'),
 *     F\_stream_operation('map', 'Function -> List -> List', $map)
 * ];
 *
 * F\_stream($operations, 11); //=> [
 *     'data' => 11,
 *     'type' => 'Number',
 *     'result' => 11,
 *     'resolved' => true,
 *     'operations' => [
 *         'length' => [
 *             [
 *                 'name' => 'length',
 *                 'signatures' => [['List', 'Number'], ['Array', 'Number']],
 *                 'fn' => 'count'
 *             ],
 *             [
 *                 'name' => 'length',
 *                 'signatures' => [['String', 'Number']],
 *                 'fn' => 'strlen'
 *             ]
 *         ],
 *         'map' => [
 *             [
 *                 'name' => 'map',
 *                 'signatures' => [['Function', 'List', 'List']],
 *                 'fn' => $map
 *             ]
 *         ]
 *     ],
 *     'transformations' => []
 * ]
 * ```
 *
 * @param  array $operations
 * @param  mixed $data
 * @return array
 */
function _stream($operations, $data) {
    return _stream_make(
        map(_f('_stream_validate_operations'), groupBy(get('name'), $operations)),
        $data,
        [],
        type($data),
        $data,
        true
    );
}

/**
 * Validates a list of operations having the same name.
 * It throws an error if a signature is duplicated.
 * ```php
 * F\_stream_validate_operations([
 *     [
 *         'name' => 'length',
 *         'signatures' => [['List', 'Number'], ['Array', 'Number']],
 *         'fn' => 'count'
 *     ],
 *     [
 *         'name' => 'length',
 *         'signatures' => [['String', 'Number']],
 *         'fn' => 'strlen'
 *     ]
 * ]); //=> [
 *     [
 *         'name' => 'length',
 *         'signatures' => [['List', 'Number'], ['Array', 'Number']],
 *         'fn' => 'count'
 *     ],
 *     [
 *         'name' => 'length',
 *         'signatures' => [['String', 'Number']],
 *         'fn' => 'strlen'
 *     ]
 * ]
 *
 * F\_stream_validate_operations([
 *     [
 *         'name' => 'length',
 *         'signatures' => [['List', 'Number'], ['Array', 'Number']],
 *         'fn' => 'count'
 *     ],
 *     [
 *         'name' => 'length',
 *         'signatures' => [['String', 'Number'], ['List', 'Number']],
 *         'fn' => 'strlen'
 *     ]
 * ]); // throws "Stream: signatures of the operation 'length' are duplicated or ambiguous"
 * ```
 *
 * @param  array $operations
 * @return array
 */
function _stream_validate_operations($operations) {
    $signatures = chain(get('signatures'), $operations);
    if (length($signatures) != length(uniqueBy(equalBy(map(init())), $signatures))) {
        _stream_throw_error('ambiguous-signatures', get('name', head($operations)));
    }
    return $operations;
}

/**
 * Applies an operation (adds a transformation) to a stream.
 * ```php
 * $operations = [
 *     F\_stream_operation('length', 'List|Array -> Number', 'count'),
 *     F\_stream_operation('length', 'String -> Number', 'strlen'),
 *     F\_stream_operation('map', 'Function -> List -> List', F\map())
 * ];
 *
 * $stream = F\_stream($operations, [1, 2, 3]);
 *
 * F\_stream_apply_operation('length', [], $stream); //=> [
 *     'data' => [1, 2, 3],
 *     'type' => 'Number',
 *     'result' => null,
 *     'resolved' => false,
 *     'operations' => [
 *         'length' => [
 *             [
 *                 'name' => 'length',
 *                 'signatures' => [['List', 'Number'], ['Array', 'Number']],
 *                 'fn' => 'count'
 *             ],
 *             [
 *                 'name' => 'length',
 *                 'signatures' => [['String', 'Number']],
 *                 'fn' => 'strlen'
 *             ]
 *         ],
 *         'map' => [
 *             [
 *                 'name' => 'map',
 *                 'signatures' => [['Function', 'List', 'List']],
 *                 'fn' => F\map()
 *             ]
 *         ]
 *     ],
 *     'transformations' => [
 *         [
 *             'operations' => [[
 *                 'name' => 'length',
 *                 'signatures' => [['List', 'Number']],
 *                 'fn' => 'count'
 *             ]],
 *             'args' => []
 *         ]
 *     ]
 * ]
 *
 * F\_stream_apply_operation('foo', [], $stream); // throws "Stream: call to unknown operation 'foo'"
 * F\_stream_apply_operation('length', [5], $stream); // throws "Stream: wrong arguments (Number, List) given to operation 'length'"
 * F\_stream_apply_operation('map', [], $stream); // throws "Stream: wrong arguments (List) given to operation 'map'"
 * F\_stream_apply_operation('map', [[1, 2]], $stream); // throws "Stream: wrong arguments (List, List) given to operation 'map'"
 *
 * ```
 *
 * @signature String -> [Any] -> Stream -> Stream
 * @param  string $name
 * @param  array $args
 * @param  array $stream
 * @return array
 */
function _stream_apply_operation($name, $args, $stream) {
    $operations = getPath(['operations', $name], $stream);
    if (null == $operations) {
        _stream_throw_error('unknown-operation', $name);
    }

    $argsTypes = append(get('type', $stream), map(type(), $args));
    $validOperations = filter(_stream_operation_is_applicable($argsTypes),
        chain(_f('_stream_split_operation_signatures'), $operations));
    if (0 == length($validOperations)) {
        _stream_throw_error('wrong-operation-args', $argsTypes, $name);
    }

    $returnTypes = map(_f('_stream_return_type_of_operation'), $validOperations);
    $returnType = reduce(_f('_stream_merge_types'), head($returnTypes), $returnTypes);
    return _stream_make(
        get('operations', $stream), // operations
        get('data', $stream), // data
        append([
            'operations' => $validOperations,
            'args' => $args
        ], get('transformations', $stream)), // transformations
        $returnType, // type
        null, // result
        false // resolved
    );
}

/**
 * Splits an operation with multiple signatures into a list of operation; each having one signature.
 * ```php
 * F\_stream_split_operation_signatures([
 *     'name' => 'length',
 *     'signatures' => [['List', 'Number'], ['Array', 'Number']],
 *     'fn' => 'count'
 * ]); //=> [
 *     [
 *         'name' => 'length',
 *         'signatures' => [['List', 'Number']],
 *         'fn' => 'count'
 *     ],
 *     [
 *         'name' => 'length',
 *         'signatures' => [['Array', 'Number']],
 *         'fn' => 'count'
 *     ]
 * ]
 * ```
 *
 * @signature Operation -> [Operation]
 * @param  array $operation
 * @return array
 */
function _stream_split_operation_signatures($operation) {
    $name = get('name', $operation);
    $fn = get('fn', $operation);
    return map(function($signature) use($name, $fn) {
        return [
            'name' => $name,
            'fn' => $fn,
            'signatures' => [$signature]
        ];
    }, get('signatures', $operation));
}

/**
 * Checks if the operation can be applied with the given arguments types.
 *
 * ```php
 * $isApplicable = F\_stream_operation_is_applicable(['Number', 'Number']);
 * $isApplicable(F\_stream_operation('add', 'Number -> Number -> Number', F\plus())); //=> true
 * $isApplicable(F\_stream_operation('length', 'List|Array|String -> Number', F\length())); //=> false
 * F\_stream_operation_is_applicable(
 *     ['List'],
 *     F\_stream_operation('length', 'List|Array|String -> Number', F\length())
 * ); //=> true
 * F\_stream_operation_is_applicable(
 *     ['String'],
 *     F\_stream_operation('length', 'List|Array|String -> Number', F\length())
 * ); //=> true
 * F\_stream_operation_is_applicable(
 *     ['Number'],
 *     F\_stream_operation('length', 'List|Array|String -> Number', F\length())
 * ); //=> false
 * F\_stream_operation_is_applicable(
 *     ['Number', 'String'],
 *     F\_stream_operation('fill', 'Number -> Any -> List', function(){})
 * ); //=> true
 * F\_stream_operation_is_applicable(
 *     ['Any', 'String'],
 *     F\_stream_operation('fill', 'Number -> Any -> List', function(){})
 * ); //=> true
 * ```
 *
 * @signature [String] -> Operation -> Boolean
 * @param  array $argsTypes
 * @param  array $operation
 * @return bool
 */
function _stream_operation_is_applicable() {
    static $operationIsApplicable = false;
    $operationIsApplicable = $operationIsApplicable ?: curry(function($argsTypes, $operation) {
        return null !== findIndex(function($signature) use($argsTypes) {
            $types = init($signature);
            if (length($types) == length($argsTypes)) {
                return allSatisfies(function($pair) {
                    return 'Any' == $pair[0] || 'Any' == $pair[1] || $pair[0] == $pair[1];
                }, pairsFrom($types, $argsTypes));
            }
            return false;
        }, get('signatures', $operation));
    });
    return _apply($operationIsApplicable, func_get_args());
}

/**
 * Gets the return type of an operation having a single signature.
 *
 * ```php
 * F\_stream_return_type_of_operation(F\_stream_operation(
 *     'count', 'List -> Number'
 * )); //=> 'Number'
 * F\_stream_return_type_of_operation(F\_stream_operation(
 *     'count', 'List ->Function -> String'
 * )); //=> 'String'
 * F\_stream_return_type_of_operation(F\_stream_operation(
 *     'count', 'List ->Function -> Any'
 * )); //=> 'Any'
 * ```
 *
 * @signature Operation -> String
 * @param  array $operation
 * @return string
 */
function _stream_return_type_of_operation($operation) {
    return last(getPath(['signatures', 0], $operation));
}

/**
 * Returns `$type1` if types are equal and `Any` if not.
 *
 * ```php
 * F\_stream_merge_types('Number', 'Number'); //=> 'Number'
 * F\_stream_merge_types('Number', 'String'); //=> 'Any'
 * F\_stream_merge_types('Any', 'String'); //=> 'Any'
 * ```
 *
 * @signature String -> String -> String
 * @param  string $type1
 * @param  string $type2
 * @return string
 */
function _stream_merge_types($type1, $type2) {
    return ($type1 == $type2)
        ? $type1
        : 'Any';
}

/**
 * Computes the result of a stream.
 *
 * ```php
 * $operations = [
 *     F\_stream_operation('length', 'List|Array -> Number', 'count'),
 *     F\_stream_operation('length', 'String -> Number', 'strlen'),
 *     F\_stream_operation('map', 'Function -> List -> List', F\map()),
 *     F\_stream_operation('reduce', 'Function -> Any -> List -> Any', F\reduce()),
 *     F\_stream_operation('increment', 'Number -> Number', F\plus(1)),
 *     F\_stream_operation('upperCase', 'String -> String', F\upperCase()),
 *     F\_stream_operation('toString', 'Any -> String', F\toString()),
 *     F\_stream_operation('head', 'List -> Any', F\head())
 * ];
 *
 * $stream = F\_stream($operations, [1, 2, 3]);
 * $stream = F\_stream_apply_operation('length', [], $stream);
 * $stream = F\_stream_resolve($stream);
 * F\get('resolved', $stream); //=> true
 * F\get('result', $stream); //=> 3
 *
 * $stream = F\_stream($operations, [1, 2, 3]);
 * $stream = F\_stream_apply_operation('map', [F\plus(2)], $stream); // [3, 4, 5]
 * $stream = F\_stream_apply_operation('reduce', [F\plus(), 0], $stream); // 12
 * $stream = F\_stream_apply_operation('increment', [], $stream); // 13
 * $stream = F\_stream_resolve($stream);
 * F\get('resolved', $stream); //=> true
 * F\get('result', $stream); //=> 13
 *
 * $stream = F\_stream($operations, []);
 * $stream = F\_stream_apply_operation('head', [], $stream); // null
 * $stream = F\_stream_apply_operation('increment', [], $stream); // Error
 * $stream = F\_stream_apply_operation('toString', [], $stream); // Error
 * $stream = F\_stream_resolve( $stream); // throws "Stream: operation 'increment' could not be called with arguments types (Null); expected types are (Number)"
 *
 * ```
 *
 * @signature Stream -> Stream
 * @param  array $stream
 * @return array
 */
function _stream_resolve($stream) {
    if (get('resolved', $stream))
        return $stream;
    $transformations = get('transformations', $stream);
    $transformation = head($transformations);
    if (null === $transformation) {
        return _stream_make(
            get('operations', $stream), // operations
            get('data', $stream), // data
            get('transformations', $stream), // transformations
            get('type', $stream), // type
            get('data', $stream), // result
            true // resolved
        );
    }

    $args = append(get('data', $stream), get('args', $transformation));
    $argsTypes = map(type(), $args);
    $operations = get('operations', $transformation);
    $applicableOperations = filter(_stream_operation_is_applicable($argsTypes), $operations);
    if (empty($applicableOperations)) {
        $types = map(pipe(get('signatures'), head(), init()), $operations);
        _stream_throw_error('wrong-transformation-args', getPath([0, 'name'], $operations), $argsTypes, $types);
    }

    return _stream_resolve(_stream_make(
        get('operations', $stream), // operations
        _apply(getPath([0, 'fn'], $applicableOperations), $args), // data
        tail($transformations), // transformations
        get('type', $stream), // type
        null, // result
        false // resolved
    ));
}

/**
 * Applies a function to a single argument.
 * To be used as the `then()` method of Stream.
 *
 * @signature (a -> b) -> a -> b
 * @param  callable $fn
 * @param  mixed $arg
 * @return mixed
 */
function _stream_then($fn, $arg) {
    return _apply($fn, [$arg]);
}

