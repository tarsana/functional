<?php namespace Tarsana\Functional;

/**
 * Stream is a lazy data container.
 */
class Stream {

    /**
     * The list of predefined operations.
     *
     * @var array
     */
    protected static $operations = null;

    /**
     * The internal Stream structure described here: src/Internal/_stream.php
     *
     * @var array
     */
    protected $stream;

    /**
     * Loads the stream operations from the file src/Internal/_stream_operations.php
     * Executed when the first Stream instance is created.
     *
     * @return void
     */
    public static function init()
    {
        if (null === self::$operations) {
            self::$operations = require __DIR__ . '/../Internal/_stream_operations.php';
        }
    }

    /**
     * Creates a new Stream.
     *
     * @param  mixed $data
     * @return Tarsana\Functional\Stream
     */
    public static function of($data)
    {
        return new Stream(_stream(self::$operations, $data));
    }

    /**
     * Adds a new operation to the Stream class.
     *
     * @param  string $name
     * @param  string $signature
     * @param  callable $fn
     * @return void
     */
    public static function operation($name, $signatures, $fn = null)
    {
        if (! is_array($signatures)) {
            $signatures = [$signatures];
        }
        foreach ($signatures as $signature) {
            self::$operations[] = _stream_operation($name, $signature, $fn);
        }
    }

    /**
     * Checks if the Stream class has an operation with the given name.
     *
     * @param  string  $name
     * @return boolean
     */
    public static function hasOperation($name)
    {
        return contains($name, map(get('name'), self::$operations));
    }

    /**
     * Removes one or many operation from the Stream class.
     *
     * @param  string $name
     * @return void
     */
    public static function removeOperations($name)
    {
        $names = func_get_args();
        self::$operations = filter(function($operation) use($names) {
            return !in_array(get('name', $operation), $names);
        }, self::$operations);
    }

    /**
     * Creates a new Stream with some data.
     *
     * @param mixed $data
     */
    protected function __construct($stream)
    {
        $this->stream = $stream;
    }

    /**
     * Returns the type of contained data in the stream.
     *
     * @return string
     */
    public function type()
    {
        return get('type', $this->stream);
    }

    /**
     * Apply all the transformations in the stream and returns the result.
     *
     * @return mixed
     * @throws Tarsana\Functional\Error
     */
    public function result()
    {
        return get('result', _stream_resolve($this->stream));
    }

    /**
     * Adds a new transformation to the stream.
     *
     * @param  string $name The name of the operation
     * @param  array  $args
     * @return Tarsana\Functional\Stream
     */
    public function __call($name, $args)
    {
        return new Stream(_stream_apply_operation($name, $args, $this->stream));
    }

    /**
     * Returns the string representation of the stream.
     *
     * @return string
     */
    public function __toString()
    {
        return "[Stream of {$this->type()}]";
    }

}

Stream::init();
