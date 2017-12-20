<?php

namespace Bondacom\antenna\Exceptions;

use Throwable;

class AntennaSaveException extends AntennaException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}