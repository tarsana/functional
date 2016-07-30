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
     *
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
        return "Stream({$this->type})";
    }

    /**
     * Executes the operations and returns the resulting data.
     * ```php
     * $s = Stream::of(55)->then(plus(5));
     * $s->get(); // 60
     * $s = Stream::of([1, 2, 3])->length();
     * $s->get(); // 3
     * ```
     *
     * @signature Stream(a) -> a
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
     * ```php
     * Stream::of([1, 2, 3])->map(function($n){
     *    return $n * $n;
     * })->get() // [1, 4, 9]
     * ```
     *
     * @signature Stream([a]) -> (a -> b) -> Stream([b])
     * @param  callable $fn
     * @return Stream
     */
    public function map (callable $fn)
    {
        return Stream::apply('map', [$fn], $this);
    }

    /**
     * Filters items of the stream.
     * ```php
     * Stream::of(['1', null, 2, 'hi'])
     *     ->filter('is_numeric')
     *     ->get() // ['1', 2]
     * ```
     *
     * @signature Stream([a]) -> (a -> Boolean) -> Stream([a])
     * @param  callable $predicate
     * @return Stream
     */
    public function filter (callable $predicate)
    {
        return Stream::apply('filter', [$predicate], $this);
    }

    /**
     * Reduces the content of the stream.
     * ```php
     * Stream::of([1, 2, 3, 4])
     *     ->reduce('Tarsana\\Functional\\plus', 0)
     *     ->get() // 10
     * ```
     *
     * @signature Stream([a]) -> (* -> a -> *) -> * -> Stream(*)
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
     * This is called `flatMap` in other libraries.
     * ```php
     * Stream::of(['Hello you', 'How are you'])
     *     ->chain(split(' '))
     *     ->get() // ['Hello', 'you', 'How', 'are', 'you']
     * ```
     *
     * @signature Stream([a]) -> (a -> [b]) -> Stream([b])
     * @param callable $fn
     * @return Stream
     */
    public function chain ($fn)
    {
        return Stream::apply('chain', [$fn], $this);
    }

    /**
     * Returns the length of the stream.
     * ```php
     * Stream::of(['Hello you', 'How are you'])
     *     ->length()
     *     ->get() // 2
     * Stream::of('Hello you')
     *     ->length()
     *     ->get() // 9
     * ```
     *
     * @signature Stream([*]) -> Number
     * @signature Stream(String) -> Number
     * @return Stream
     */
    public function length ()
    {
        return Stream::apply('length', [], $this);
    }

    /**
     * Takes a number of items from the stream.
     * ```php
     * Stream::of([1, 2, 3, 4, 5])
     *     ->take(3)
     *     ->get() // [1, 2, 3]
     * Stream::of('Hello World')
     *     ->take(5)
     *     ->get() // 'Hello'
     * ```
     *
     * @signature Stream([a]) -> Number -> Stream([a])
     * @signature Stream(String) -> Number -> Stream(String)
     * @param int $number
     * @return Stream
     */
    public function take ($number)
    {
        return Stream::apply('take', [$number], $this);
    }

    /**
     * Applies a custom functions on the content of the stream.
     * ```php
     * Stream::of('Hello')
     *     ->then('strtoupper')
     *     ->get() // 'HELLO'
     *
     * Stream::of('   HelLO ')
     *     ->then('trim', 'strtolower')
     *     ->get() // 'hello'
     * ```
     *
     * @signature Stream(a) -> (a -> b) -> Stream(b)
     * @param callable $fn
     * @return Stream
     */
    public function then ($fn)
    {
        $result = $this;
        foreach (func_get_args() as $fn) {
            $result = Stream::apply('apply', $fn, $result);
        }
        return $result;
    }

    /**
     * Calls a method of the contained object and
     * returns a stream with the resulting value.
     * ```php
     * class ForStreamTest {
     *    protected $value;
     *    public function init($value) {$this->value = $value; return $this;}
     *    public function add($value) {$this->value += $value; return $this;}
     *    public function addTwo($a, $b) {$this->value += $a + $b; return $this;}
     *    public function mult($value) { $this->value *= $value; return $this;}
     *    public function value() {return $this->value;}
     *    protected function reset() {$this->value = 0; return $this;}
     *    private function clear() {$this->value = null; return $this;}
     * }
     *
     * Stream::of(new ForStreamTest)
     *     ->call('init', 5)
     *     ->call('add', 1)
     *     ->call('addTwo', 1, 1)
     *     ->call('mult', 2)
     *     ->call('value')
     *     ->get(); // 16
     *
     * Stream::of(new ForStreamTest)
     *     ->call('reset')
     *     ->get(); // [Error: Method 'reset' of [Object] is not accessible]
     *
     * Stream::of(new ForStreamTest)
     *     ->call('clear')
     *     ->get(); // [Error: Method 'clear' of [Object] is not accessible]
     *
     * Stream::of(new ForStreamTest)
     *     ->call('missing')
     *     ->get(); // [Error: Method 'missing' of [Object] is not found]
     * ```
     *
     * @signature Stream(a) -> (String, ...) -> Stream(*)
     * @param  string $method
     * @param  mixed|null $args...
     * @return Stream
     */
    public function call ($method)
    {
        $args = tail(func_get_args());
        return Stream::apply('apply', function($data) use($method, $args) {
            if (is_callable([$data, $method]))
                return call_user_func_array([$data, $method], $args);
            $text = toString($data);
            if (method_exists($data, $method))
                return Error::of("Method '{$method}' of {$text} is not accessible");
            return Error::of("Method '{$method}' of {$text} is not found");
        }, $this);
    }

    /**
     * Calls a method of the contained object and returns a stream
     * with same object ignoring the result of the method.
     * ```php
     * class ForStreamTest {
     *    protected $value;
     *    public function init($value) {$this->value = $value;}
     *    public function add($value) {$this->value += $value;}
     *    public function addTwo($a, $b) {$this->value += $a + $b;}
     *    public function mult($value) { $this->value *= $value;}
     *    public function value() {return $this->value;}
     *    protected function reset() {$this->value = 0;}
     *    private function clear() {$this->value = null;}
     * }
     *
     * Stream::of(new ForStreamTest)
     *     ->run('init', 5)
     *     ->run('add', 1)
     *     ->run('addTwo', 1, 1)
     *     ->run('mult', 2)
     *     ->run('value')
     *     ->get(); // 16
     *
     * Stream::of(new ForStreamTest)
     *     ->run('reset')
     *     ->get(); // [Error: Method 'reset' of [Object] is not accessible]
     *
     * Stream::of(new ForStreamTest)
     *     ->run('clear')
     *     ->get(); // [Error: Method 'clear' of [Object] is not accessible]
     *
     * Stream::of(new ForStreamTest)
     *     ->run('missing')
     *     ->get(); // [Error: Method 'missing' of [Object] is not found]
     * ```
     *
     * @signature Stream(a) -> (String, ...) -> Stream(a)
     * @param  string $method
     * @param  mixed|null $args...
     * @return Stream
     */
    public function run ($method)
    {
        $args = tail(func_get_args());
        return Stream::apply('apply', function($data) use($method, $args) {
            if (is_callable([$data, $method])) {
                call_user_func_array([$data, $method], $args);
                return $data;
            }
            $text = toString($data);
            if (method_exists($data, $method))
                return Error::of("Method '{$method}' of {$text} is not accessible");
            return Error::of("Method '{$method}' of {$text} is not found");
        }, $this);
    }

}
