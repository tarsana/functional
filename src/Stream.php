<?php namespace Tarsana\Functional;

/**
 * Stream is a lazy data container.
 */
class Stream {

    /**
     * Data type transformations done by each operation.
     *
     * @var array
     */
    protected static $transformations = [
        'map' => [
            'List' => 'List',
            'Array' => 'Array',
            'ArrayObject' => 'ArrayObject'
        ],
        'filter' => [
            'List' => 'List',
            'Array' => 'Array',
            'ArrayObject' => 'ArrayObject'
        ],
        'reduce' => [
            'List' => 'Unknown',
            'Array' => 'Unknown',
            'ArrayObject' => 'Unknown'
        ],
        'chain' => [
            'List' => 'List',
            'Array' => 'Array',
            'ArrayObject' => 'ArrayObject'
        ],
        'take' => [
            'String' => 'String',
            'List' => 'List',
            'Array' => 'Array',
            'ArrayObject' => 'ArrayObject'
        ],
        'length' => [
            'String' => 'Number',
            'List' => 'Number',
            'Array' => 'Number',
            'ArrayObject' => 'Number'
        ],
        'apply' => [
            'Unknown' => 'Unknown'
        ]
    ];

    /**
     * The internal data of the stream.
     *
     * @var mixed
     */
    protected $data;

    /**
     * Type of the data after applying operations.
     *
     * @var string
     */
    protected $type;

    /**
     * Operations to apply to data.
     *
     * @var array
     */
    protected $operations;

    /**
     * Creates a new Stream with the provided $data.
     * ```php
     * Stream::of(1); // Stream(1)
     * Stream::of(1, 'Hello'); // Stream([1, 'Hello'])
     * Stream::of([1, 2, 3]); // Stream([1, 2, 3])
     * ```
     * @signature a -> Stream(a)
     * @param  mixed $data
     * @return Stream
     */
    public static function of ($data)
    {
        $data = func_get_args();
        if (count($data) == 1)
            $data = $data[0];
        return new Stream($data, [], type($data));
    }

    /**
     * Stream factory function for internal use.
     *
     * @param  mixed $data
     * @param  array $operations
     * @return Stream
     */
    protected static function with ($data, $operations, $type)
    {
        return new Stream($data, $operations, $type);
    }

    /**
     * Re-arrange operations to have the optimal execution.
     *
     * @param  array $operations
     * @return array|Error
     */
    protected static function optimize ($operations)
    {
        // TODO: ...
        return $operations;
    }

    /**
     * Runs the operations over data and returns the result.
     *
     * @param  array $operations
     * @param  mixed $data
     * @return mixed|Error
     */
    protected static function execute ($operations, $data)
    {
        if (length($operations) == 0)
            return $data;
        $operations = apply('Tarsana\\Functional\\pipe', map(function($operation){
            if ($operation['name'] == 'apply') {
                return $operation['args'];
            }
            return (length($operation['args']) > 0)
                ? apply('Tarsana\\Functional\\'.$operation['name'], $operation['args'])
                : 'Tarsana\\Functional\\'.$operation['name'];
        }, $operations));
        return $operations($data);
    }

    /**
     * Checks if an operation can be applied to a specific type.
     *
     * @param  string $operation
     * @param  string $type
     * @return bool
     */
    protected static function canApply ($operation, $type)
    {
        return isset(Stream::$transformations[$operation]) && (
            $type == 'Unknown' ||
            array_key_exists($type, Stream::$transformations[$operation]) ||
            array_key_exists('Unknown', Stream::$transformations[$operation])
        );
    }

    /**
     * Gets the return type of an operation when applied to a specific type.
     *
     * @param  string $operation
     * @param  string $type
     * @return bool
     */
    protected static function returnOf ($operation, $type)
    {
        return isset(Stream::$transformations[$operation][$type])
            ? Stream::$transformations[$operation][$type]
            : 'Unknown';
    }

    /**
     * Adds an operation to a stream.
     *
     * @param  string $operation
     * @param  mixed $args
     * @param  Stream $stream
     * @return Stream
     */
    protected static function apply ($operation, $args, $stream)
    {
        if ($stream->type == 'Error') {
            return Stream::of(Error::of("Could not apply {$operation} to {$stream->type}", $stream->data));
        }
        if (! Stream::canApply($operation, $stream->type)) {
            $data = toString($stream->data);
            return Stream::of(Error::of("Could not apply {$operation} to {$stream->type}({$data})"));
        }
        return Stream::with(
            $stream->data,
            Stream::optimize(append(['name' => $operation, 'args' => $args], $stream->operations)),
            Stream::returnOf($operation, $stream->type)
        );
    }

    /**
     * Creates a new Stream.
     *
     * @param mixed $data
     */
    protected function __construct ($data, $operations, $type)
    {
        $this->data = $data;
        $this->type = $type;
        $this->operations = $operations;
    }

    public function __toString()
    {
        return "Stream({$this->type})";
    }

    /**
     * Executes the operations and returns the resulting data.
     *
     * @return mixed
     */
    public function get ()
    {
        if ($this->type == 'Error')
            return $this->data;
        return Stream::execute($this->operations, $this->data);
    }

    /**
     * Applies a function to items of the stream.
     *
     * @param  callable $fn
     * @return Stream
     */
    public function map (callable $fn)
    {
        return Stream::apply('map', [$fn], $this);
    }

    /**
     * Filters items of the stream.
     *
     * @param  callable $predicate
     * @return Stream
     */
    public function filter (callable $predicate)
    {
        return Stream::apply('filter', [$predicate], $this);
    }

    /**
     * Reduces the content of the stream.
     *
     * @param callable $fn
     * @param mixed $initial
     * @return Stream
     */
    public function reduce ($fn, $initial)
    {
        return Stream::apply('reduce', [$fn, $initial], $this);
    }


    /**
     * Chains a function over the content of the stream.
     *
     * @param callable $fn
     * @return Stream
     */
    public function chain ($fn)
    {
        return Stream::apply('chain', [$fn], $this);
    }

    /**
     * Returns the length of the stream.
     *
     * @return Stream
     */
    public function length ()
    {
        return Stream::apply('length', [], $this);
    }

    /**
     * Takes a number of items from the stream.
     *
     * @param int $number
     * @return Stream
     */
    public function take ($number)
    {
        return Stream::apply('take', [$number], $this);
    }

    /**
     * Applies a custom function on the content of the stream.
     *
     * @param callable $fn
     * @return Stream
     */
    public function then ($fn)
    {
        return Stream::apply('apply', $fn, $this);
    }

}
