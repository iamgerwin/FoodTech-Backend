<?php

namespace App;

use Exception;

/**
 * Class GenericException
 *
 * A generic exception for catching general errors.
 */
class GenericException extends Exception
{
    /**
     * GenericException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "A generic error occurred.", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
