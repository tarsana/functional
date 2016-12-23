<?php namespace Tarsana\Functional;

/**
 * This class represents an error. It extends the `Exception` class and thus can be thrown.
 * @class
 */
class Error extends \Exception {
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
    public static function of ($message, $code = 0, Error $error = null)
    {
        return new Error($message, $code, $error);
    }

    /**
     * Returns a string representation of the error.
     *
     * @return string
     */
    public function __toString()
    {
        return "[Error: {$this->getMessage()}]";
    }
}
