<?php

namespace App\Exceptions;

use Exception;

class PhoneLimitReachedException extends Exception
{
    public function __construct()
    {
        parent::__construct('A demographic record cannot have more than two phone numbers.');
    }
}
