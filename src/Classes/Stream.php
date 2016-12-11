<?php namespace Tarsana\Functional;

/**
 * Stream is a lazy data container.
 */
class Stream {

    protected static $types = [
        'null',
        'boolean',
        'number',
        'string',
        'resource',
        'function',
        'list', // array of which all keys are integers
        'array',
        'object',
        'any'
    ];

    /**
     * Defined operations.
     * [
     *   'name' => [
     *     'callable' => Function,
     *     'signatures' => [
     *       ['Type1', 'Type2', ...],
     *       ['Type1', 'Type2', ...],
     *       ...
     *     ]
     *   ],
     *   ...
     * ]
     * @var array
     */
    protected static $operations = [];

    /**
     * The internal data of the stream.
     *
     * @var mixed
     */
    protected $data;

    /**
     * Type of the data after applying transformations.
     *
     * @var string
     */
    protected $type;

    /**
     * Does the stream throws exception instead of returning Error ?
     *
     * @var bool
     */
    protected $throwsException;

    /**
     * Transformations to apply to data.
     * [
     *   [
     *     'callable' => Function,
     *     'args' => [...]
     *   ],
     *   ...
     * ]
     * @var array
     */
    protected $transformations;

    /**
     * Creates a new Stream with the provided $data.
     * ```php
     * Stream::of(1); // Stream(Number)
     * Stream::of([1, 2, 3]); // Stream(List)
     * ```
     *
     * @signature a -> Stream(a)
     * @param  mixed $data
     * @return Stream
     */
    public static function of ($data)
    {
        return new Stream($data);
    }

    /**
     * Define a new operation which can be used in all Streams.
     * ```php
     * Stream::operation('length', 'List -> Number', 'count');
     * Stream::of([1, 2, 4])->length()->get(); // 3
     * ```
     *
     * @param  string $name
     * @param  string|array $signatures
     * @param  callable $fn
     * @return void
     */
    public static function operation($name, $signatures, $fn = null)
    {
        if (static::hasOperation($name)) {
            static::throwError('duplicated-operation', $name);
        }

        $signatures = is_string($signatures) ? [$signatures] : $signatures;
        $fn = $fn ?: $name;

        if (! is_callable($fn)) {
            static::throwError('unknown-callable', $fn);
        }

        self::$operations[$name] = [
            'callable' => $fn,
            'signatures' => chain(_f('Stream::makeSignature'), $signatures)
        ];
    }

    public static function removeOperations($name)
    {
        foreach (func_get_args() as $operationName) {
            if (static::hasOperation($operationName)) {
                unset(static::$operations[$operationName]);
            }
        }
    }

    public static function hasOperation($name)
    {
        return array_key_exists($name, static::$operations);
    }

    public static function makeSignature($text)
    {
        $ensureType = function($str) use($text) {
            if (! contains($str, static::$types)) {
                static::throwError('invalid-signature', $text);
            }
            return $str;
        };

        $parts = map(pipe(lowerCase(), split('|'), map(pipe('trim', $ensureType))), split('->', $text));
        // $text  = 'Number|List -> Number -> String|Array -> Number'
        // $parts = [['number', 'list'], ['number'], ['string', 'array'], ['number']]

        if (length($parts) < 2) {
            static::throwError('invalid-signature', $text);
        }

        $parts = reduce(function($result, $part){
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

        return $parts;
    }

    protected static function makeCallable($fn)
    {
        if (! is_callable($fn)) {
            static::throwError('unknown-callable', $fn);
        }
        return $fn;
    }

    protected function returnTypeOf($name, $types)
    {
        $signatures = static::$operations[$name]['signatures'];
        $applicable = find(pipe(init(), equals($types)), $signatures);

        if (null === $applicable) {
            static::throwError('invalid-args', $name, $types, $signatures);
        }

        return last($applicable);
    }

    protected static function throwError($type = 'unknown')
    {
        $params = tail(func_get_args());
        $msg = 'Stream: unknown error happened';
        switch ($type) {
            case 'unknown-callable':
                $fn = is_string($params[0]) ? $params[0] : toString($params[0]);
                $msg = "Stream: unknown callable '{$fn}'";
            break;
            case 'invalid-signature':
                $msg = "Stream: invalid signature '{$params[0]}' it should follow the syntax 'TypeArg1 -> TypeArg2 -> ... -> ReturnType' and types to use are Boolean, Number, String, Resource, Function, List, Array, Object";
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
     * Creates a new Stream.
     *
     * @param mixed $data
     */
    protected function __construct ($data, $transformations = [], $type = null, $throwsException = false)
    {
        $this->data = $data;
        $this->type = $type ?: lowerCase(type($data));
        $this->transformations = $transformations;
        $this->throwsException = $throwsException;
    }

    /**
     * Returns a string representation of a Stream.
     * ```php
     * $s = Stream::of(55);
     * echo $s; // Outputs: Stream(Number)
     * $s = Stream::of([1, 2, 3]);
     * echo $s; // Outputs: Stream(List)
     * $s = Stream::of(Error::of('Ooops'));
     * echo $s; // Outputs: Stream(Error)
     * ```
     *
     * @signature Stream(*) -> String
     * @return string
     */
    public function __toString()
    {
        return "Stream({$this->type()})";
    }

    /**
     * Returns the type of the contained data after applying operations.
     * ```php
     * Stream::of(null))->type(); // 'Null'
     * Stream::of(true))->type(); // 'Boolean'
     * Stream::of(5.2))->type(); // 'Number'
     * Stream::of('Foo'))->type(); // 'String'
     * Stream::of(fopen('php://memory', "r")))->type(); // 'Resource'
     * Stream::of(F\map()))->type(); // 'Function'
     * Stream::of([1, 2, 3]))->type(); // 'List'
     * Stream::of(['foo' => 'bar']))->type(); // 'Array'
     * Stream::of((object)['foo' => 'bar']))->type(); // 'Object'
     * ```
     *
     * @signature Stream(a) -> String
     * @return string
     */
    public function type()
    {
        return ucfirst($this->type);
    }

    /**
     * Applies operations and returns the resulting data.
     * ```php
     * Stream::of('Hello')->get(); // 'Hello'
     *
     * ```
     *
     * @signature Stream(a) -> a
     * @return mixed
     */
    public function get ()
    {
        $data = $this->data;

        foreach ($this->transformations as $transformation) {
            $callable = static::$operations[$transformation['operation']]['callable'];
            $args = append($data, $transformation['args']);
            $data = apply($callable, $args);
            if ($data instanceof Error) {
                $name = $transformation['operation'];
                $args = toString($args);
                $msg  = $data->getMessage();
                $data = Error::of(
                    "Stream: Operation '{$name}' called with arguments {$args} and returned error with message \"{$msg}\"",
                    0,
                    $data
                );
                break;
            }
        }

        if ($this->throwsException && $data instanceof Error) {
            throw $data;
        }

        return $data;
    }

    public function __call($name, $args)
    {
        if (! static::hasOperation($name)) {
            $this->throwError('unknown-operation', $name);
        }

        $transformation = [
            'operation' => $name,
            'args' => $args
        ];

        $types = append($this->type, map(pipe(type(), 'trim', lowerCase()),  $args));

        return new Stream(
            $this->data,
            append($transformation, $this->transformations),
            static::returnTypeOf($name, $types),
            $this->throwsException
        );
    }

    public function throwExceptionWhenError()
    {
        return new Stream(
            $this->data,
            $this->transformations,
            $this->type,
            true
        );
    }

    public function then($callable)
    {
        // ...
    }

}
