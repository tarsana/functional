<?php namespace Tarsana\Functional;

/**
 * This class represents an error.
 */
class Error {
    /**
     * A message describing the error.
     *
     * @var string
     */
    protected $message;

    /**
     * Creates a new Error.
     * ```php
     * $err = Error::of('Ooops !'); // [Error: Ooops !]
     * $err2 = Error::of('Second error', $err); // [Error: Second error -> Ooops !]
     * ```
     *
     * @signature String -> Error
     * @signature (String, Error) -> Error
     * @param  string     $message
     * @param  Error|null $error
     * @return Error
     */
    public static function of ($message, Error $error = null)
    {
        return new Error($message, $error);
    }

    /**
     * Creates a new Error.
     *
     * @param string     $message
     * @param Error|null $error
     */
    protected function __construct ($message, Error $error = null)
    {
        if (null != $error)
            $message = $error->message() . ' -> ' . $message;
        $this->message = $message;
    }

    /**
     * Gets the error's message.
     * ```php
     * $err = Error::of('Ooops !');
     * $err->message(); // 'Ooops !'
     * ```
     *
     * @signature Error -> String
     * @return string
     */
    public function message ()
    {
        return $this->message;
    }

    /**
     * Returns the string representation of the error.
     * ```php
     * $err = Error::of('Ooops !');
     * echo $err; // Outputs: [Error: Ooops !]
     * ```
     *
     * @signature Error -> String
     * @return string
     */
    public function __toString()
    {
        return "[Error: {$this->message}]";
    }
}
