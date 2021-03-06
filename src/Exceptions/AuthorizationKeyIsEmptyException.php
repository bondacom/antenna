<?php

namespace Bondacom\Antenna\Exceptions;

use Throwable;

class AuthorizationKeyIsEmptyException extends AntennaException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Missing OneSignal UserKey required for this action.', $code, $previous);
    }
}